<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;
use App\DmsDocument;
use App\DmsCategory;
use App\DmsKeyword;
use App\Employee;
use DB;
use App\User;
use Auth;

class DmsDocumentController extends Controller
{
    public function index(){
        $dmsDocuments = DmsDocument::where('isActive','1')->get();
        $dmsCategories = DmsCategory::where('isActive','1')->get();
        $dmsKeywords = DmsKeyword::where('isActive','1')->get();
        $departments = Department::where('isActive','1')->get();
        return view('dms_document.index', compact('dmsDocuments', 'dmsCategories', 'dmsKeywords', 'departments'));
    }

    public function create(){
        $dmsCategories = DmsCategory::where('isActive','1')->get();
        $dmsKeywords = DmsKeyword::where('isActive','1')->get();
        $departments = Department::where('isActive','1')->get();
        return view('dms_document.create', compact('dmsCategories', 'dmsKeywords', 'departments'));
    }

    public function store(Request $request){
        $request->validate([
            'document_name' => 'required|max:50|unique:dms_documents,name,NULL,id,deleted_at,NULL',
        ]);

        $files = $request->file('document_files');
        if($request->hasFile('document_files'))
        {
            foreach ($files as $documentFile) {
                $fileContents = $documentFile;
                $documentName = time() .rand('10', '100') .'.' . $documentFile->extension();
                $documentFile->move(public_path('uploads/document'), $documentName);
                $documents[] = $documentName;
            }
        }
        $dmsDocument = DmsDocument::create([
            'name' => $request->document_name,
            'dms_category_id' => $request->document_category,
            'document' => json_encode($documents)
        ]);

        foreach ($request->document_keywords as $keyword){
            $dmsDocument->keywords()->attach([$keyword]);
        }

        foreach ($request->departments as $department){
            $dmsDocument->departments()->attach([$department]);
        }

        foreach ($request->employees as $employee){
            $dmsDocument->employees()->attach([$employee]);
        }

        return redirect()->route('dms.document.index')->with('success','Dms Document created successfully!');
    }

    public function show(){
        return view('dms_document.show');
    }

    public function edit(DmsDocument $dmsDocument){
        $dmsCategories = DmsCategory::where('isActive','1')->get();
        $dmsKeywords = DmsKeyword::where('isActive','1')->get();
        $departments = Department::where('isActive','1')->get();
        $employees = $dmsDocument->employees;
        return view('dms_document.edit', compact('employees', 'dmsDocument', 'dmsCategories', 'dmsKeywords', 'departments'));
    }

    public function update(Request $request, DmsDocument $dmsDocument){
        $request->validate([
            'document_name' => 'required|max:50|unique:dms_documents,name,'.$dmsDocument->id.',id,deleted_at,NULL',
        ]);

        $dmsDocuments = json_decode($dmsDocument->document);
        foreach ($dmsDocuments as $documentDms) {
            $documents[] = $documentDms;
        }

        $files = $request->file('document_files');
        if($request->hasFile('document_files'))
        {
            foreach ($files as $documentFile) {
                $fileContents = $documentFile;
                $documentName = time() .rand('10', '100') .'.' . $documentFile->extension();
                $documentFile->move(public_path('uploads/document'), $documentName);
                $documents[] = $documentName;
            }

        }

        DmsDocument::where('id', $dmsDocument->id)->update([
            'name' => $request->document_name,
            'dms_category_id' => $request->document_category,
            'document' => json_encode($documents)
        ]);

        foreach ($request->document_keywords as $keyword){
            $dmsDocument->keywords()->sync([$keyword], false);
        }
        foreach ($request->departments as $department){
            $dmsDocument->departments()->sync([$department]);
        }
        foreach ($request->employees as $employee){
            $dmsDocument->employees()->sync([$employee], false);
        }

        return redirect()->route('dms.document.index')->with('success','Dms Document updated successfully!');
    }

    public function destroy(DmsDocument $dmsDocument){
        $dmsDocument->delete();
        return redirect()->route('dms.document.index')->with('success','Dms Document deleted successfully!');
    }

    public function download( $document){
        $file= public_path(). "/uploads/document/". $document;
        if(file_exists($file)){
            return Response::download($file);
        }else {
            return redirect()->route('dms.document.index')->with('error','Document Not Exist!');
        }
    }

    public function departmentEmployee(Request $request){
        $data = "Select Employee";
        if($request->department_ids != '') {
            $data = DB::table('employees as e')
                ->join('employee_profiles as ep', 'e.user_id', '=', 'ep.user_id')
                ->whereIn('ep.department_id', $request->department_ids)
                ->where(['e.approval_status' => '1', 'e.isactive' => 1, 'ep.isactive' => 1])
                ->select('e.id', 'e.fullname')
                ->get();
        }
        return Response::json(['success'=>true,'data'=>$data]);
    }

    public function filter(Request $request){
        $category = $request->document_category;
        $keyword = $request->document_keyword;
        $department = $request->document_department;
        if($category != '' && $keyword == '' && $department == ''){
            $dmsDocuments = DmsDocument::where('dms_category_id', $category)->with('category')->with('keywords')->get();
        }
        elseif($category == '' && $keyword != '' && $department == ''){
            $keyword = DmsKeyword::findorfail($keyword);
            $allDmsDocuments  = $keyword->documents;
            if($allDmsDocuments != ''){
                foreach ($allDmsDocuments as $dmsDocument){
                    $dmsDocuments[] = DmsDocument::where('id', $dmsDocument['id'])->with('category')->with('keywords')->first();
                }
            }
        }
        elseif($category == '' && $keyword == '' && $department != ''){
            $department = Department::findorfail($department);
            $allDmsDocuments = $department->documents;
            foreach ($allDmsDocuments as $dmsDocument){
                $dmsDocuments[] = DmsDocument::where('id', $dmsDocument['id'])->with('category')->with('keywords')->first();
            }
        }
        elseif($category != '' || $keyword != '' || $department != '') {
            $categoryDmsdocuments = [];
            $keywordDmsdocuments = [];
            $departmentDmsdocuments = [];
            if ($category != '') {
                $categoryDmsdocuments = DmsDocument::where('dms_category_id', $category)->get()->toArray();
            }

            if ($keyword != '') {
                $keyword = DmsKeyword::findorfail($keyword);
                $keywordDmsdocuments = $keyword->documents->toArray();
            }

            if ($department != '') {
                $department = Department::findorfail($department);
                $departmentDmsdocuments = $department->documents->toArray();
            }

            $allDmsDocuments = array_unique(array_merge($categoryDmsdocuments, $keywordDmsdocuments), SORT_REGULAR);
            $allDmsDocuments = array_unique(array_merge($allDmsDocuments, $departmentDmsdocuments), SORT_REGULAR);

            $filterArray = array();

            foreach ($allDmsDocuments as $index => $t) {
                if (isset($filterArray[$t["id"]])) {
                    unset($allDmsDocuments[$index]);
                    continue;
                }
                $filterArray[$t["id"]] = true;
            }

            foreach ($allDmsDocuments as $dmsDocument){
                $dmsDocuments[] = DmsDocument::where('id', $dmsDocument['id'])->with('category')->with('keywords')->first();
            }
        }
        else{
            $dmsDocuments = DmsDocument::where('isActive','1')->get();
        }
        if(isset($dmsDocuments)) {
            return  $this->returnHtml($dmsDocuments);
        }else {
            return "";
        }
    }

    public function returnHtml($dmsDocuments){
        $output="";
        foreach ($dmsDocuments as $key => $dmsDocument) {

            $output .= '<tr>' .

                '<td>' . $dmsDocument->id . '</td>' .

                '<td>' . $dmsDocument->name . '</td>' .
                '<td>' . $dmsDocument['category']->name . '</td>' .
                '<td>' .$this->keywordHtml($dmsDocument['keywords']).'</td>' .
                '<td>' . '<a href="' . route('dms.document.edit', $dmsDocument->id) . '">
                        <button class="btn bg-purple">
                            <i class="fa fa-edit"></i>
                        </button></a>' .
                '</td>' .
                '<td>' . '<form method"post" action="'.route('dms.document.destroy', $dmsDocument->id).'" onclick="return confirm(\'Are you sure you want to delete this Document?\');">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <button class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </button></form>' .
                '</td>' .
                '<td>' . '<form method"post" action="' . route('dms.document.download', $dmsDocument->id) . '">
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <button class="btn btn-secondary">
                            <i class="fa fa-download"></i>
                        </button></form>' .
                '</td>' .
                '</tr>';

        }
        return Response($output);
    }

    public function keywordHtml($keywords){
        $output = '';
        foreach ($keywords as $keyword){
            $output .= $keyword->name.',';
        }
        return $output;
    }

    public function removeDocument(DmsDocument $dmsDocument, $document){
        unlink(public_path('uploads/document/'.$document));
        $dmsDocuments = json_decode($dmsDocument->document);
        foreach ($dmsDocuments as $documentDms) {
            $documents[] = $documentDms;
        }
        if (($key = array_search($document, $documents)) !== false) {
            unset($documents[$key]);
        }
        DmsDocument::where('id', $dmsDocument->id)->update([
            'document' => json_encode($documents)
        ]);
        return Redirect::back();
    }

    public function myDocuments(){
        $employee = Employee::where('user_id',Auth::user()->id)->first();
        $dmsDocuments = $employee->documents;
        return view('dms_document.myDocument', compact('dmsDocuments'));
    }
}

