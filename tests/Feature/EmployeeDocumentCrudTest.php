<?php

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('a user can list the documents of an employee', function () {
    [$user, $employee] = employeeDocumentContext();

    $first = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'document_type' => 'citizenship',
        'document' => 'documents/citizenship.pdf',
    ]);
    $second = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'document_type' => 'pan',
        'document' => 'documents/pan.pdf',
    ]);

    Sanctum::actingAs($user);

    $this->getJson("/api/v1/employee-documents/{$employee->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'All Documents Fetched Successfully.')
        ->assertJsonPath('data.total', 2)
        ->assertJsonCount(2, 'data.data');

    $ids = collect($this->getJson("/api/v1/employee-documents/{$employee->id}")->json('data.data'))
        ->pluck('id')
        ->all();

    expect($ids)->toContain($first->id, $second->id);
});

test('a user can upload a document for an employee', function () {
    [$user, $employee] = employeeDocumentContext();

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/employee-document/{$employee->id}", [
        'document_type' => 'passport',
        'document' => 'documents/passport.pdf',
    ])
        ->assertSuccessful()
        ->assertJsonPath('data.employee_id', $employee->id)
        ->assertJsonPath('data.document_type', 'passport')
        ->assertJsonPath('data.document', 'documents/passport.pdf');

    $this->assertDatabaseHas('employee_documents', [
        'employee_id' => $employee->id,
        'document_type' => 'passport',
        'document' => 'documents/passport.pdf',
    ]);
});

test('a user can view a single employee document', function () {
    [$user, $employee] = employeeDocumentContext();

    $document = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'document_type' => 'license',
        'document' => 'documents/license.pdf',
    ]);

    Sanctum::actingAs($user);

    $this->getJson("/api/v1/employee-document/{$document->id}")
        ->assertSuccessful()
        ->assertJsonPath('data.id', $document->id)
        ->assertJsonPath('data.document_type', 'license');
});

test('a user can update an employee document', function () {
    [$user, $employee] = employeeDocumentContext();

    $document = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'document_type' => 'contract',
        'document' => 'documents/old-contract.pdf',
    ]);

    Sanctum::actingAs($user);

    $this->patchJson("/api/v1/employee-document/{$document->id}", [
        'document_type' => 'signed_contract',
        'document' => 'documents/new-contract.pdf',
    ])
        ->assertSuccessful()
        ->assertJsonPath('document_type', 'signed_contract')
        ->assertJsonPath('document', 'documents/new-contract.pdf');

    $this->assertDatabaseHas('employee_documents', [
        'id' => $document->id,
        'document_type' => 'signed_contract',
        'document' => 'documents/new-contract.pdf',
    ]);
});

test('a user can delete an employee document', function () {
    [$user, $employee] = employeeDocumentContext();

    $document = EmployeeDocument::create([
        'employee_id' => $employee->id,
        'document_type' => 'certificate',
        'document' => 'documents/certificate.pdf',
    ]);

    Sanctum::actingAs($user);

    $this->deleteJson("/api/v1/employee-document/{$document->id}")
        ->assertSuccessful()
        ->assertJsonPath('message', 'Document Deleted Successfully.');

    $this->assertDatabaseMissing('employee_documents', ['id' => $document->id]);
});

test('employee document creation validates its payload', function () {
    [$user, $employee] = employeeDocumentContext();

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/employee-document/{$employee->id}", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['document_type', 'document']);

    $this->assertDatabaseCount('employee_documents', 0);
});

test('employee document endpoints require authentication', function () {
    [, $employee] = employeeDocumentContext();

    $this->getJson("/api/v1/employee-documents/{$employee->id}")->assertUnauthorized();
});

/**
 * @return array{0: User, 1: Employee}
 */
function employeeDocumentContext(): array
{
    $company = Company::factory()->create();
    $branch = Branch::factory()->for($company)->create();

    $user = User::factory()->create();
    $userCompany = CompanyUser::create([
        'company_id' => $company->id,
        'user_id' => $user->id,
    ]);

    $employee = Employee::create([
        'company_user_id' => $userCompany->id,
        'branch_id' => $branch->id,
        'employee_code' => 'EMP-DOC-001',
        'first_name' => 'Document',
        'last_name' => 'Owner',
        'joining_date' => '2026-01-01',
        'employment_type' => 'full_time',
    ]);

    return [$user, $employee];
}
