<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employees = Employee::all();
        return response()->json($employees);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validación de los datos recibidos
        $request->validate([
            'primer_apellido' => 'required|string|max:20',
            'segundo_apellido' => 'required|string|max:20',
            'primer_nombre' => 'required|string|max:20',
            'otros_nombres' => 'nullable|string|max:50',
            'tipo_identificacion' => 'required|string|max:50',
            'numero_identificacion' => 'required|string|max:20',
            'correo_electronico' => 'required|string|max:300|unique:employees',
            'pais_empleo' => 'required|in:Colombia,Estados Unidos',
            'fecha_ingreso' => 'required|date',
            'area' => 'required|string|max:50',
            'estado' => 'string|max:10|nullable',// Puede ser nulo ya que tiene un valor por defecto en la base de datos
        ]);
        $employee = new Employee;
        //Valores de los campos
        $employee->primer_apellido = $request->input('primer_apellido');
        $employee->segundo_apellido = $request->input('segundo_apellido');
        $employee->primer_nombre = $request->input('primer_nombre');
        $employee->otros_nombres = $request->input('otros_nombres');
        $employee->tipo_identificacion = $request->input('tipo_identificacion');
        $employee->numero_identificacion = $request->input('numero_identificacion');
        $employee->correo_electronico = $request->input('correo_electronico');
        $employee->pais_empleo = $request->input('pais_empleo');
        $employee->fecha_ingreso = $request->input('fecha_ingreso');
        $employee->area = $request->input('area');
        $employee->estado = $request->input('estado', 'Activo'); // Si no se proporciona, se establece en 'Activo' por defecto

        // Guardamos el empleado en la base de datos
        $employee->save();
        // Retornamos una respuesta de éxito
        $data = [
            'message'=>'Empleado guardado correctamente',
            'employee'=>$employee
        ];
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee,$id)
    {
        //Buscamos al empleado por id
        $employee = Employee::where('id_empleado', $id)->firstOrFail();
        //Devolvemos al empleado
        return response()->json(['employee' => $employee], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee,$id)
    {
        // Valida los datos recibidos del formulario
        $request->validate([
            'primer_apellido' => 'required|string|max:20',
            'segundo_apellido' => 'required|string|max:20',
            'primer_nombre' => 'required|string|max:20',
            'otros_nombres' => 'nullable|string|max:50',
            'tipo_identificacion' => 'required|string|max:50',
            'numero_identificacion' => 'required|string|max:20',
            'correo_electronico' => 'required|email|string|max:300',
            'pais_empleo' => 'required|in:Colombia,Estados Unidos',
            'fecha_ingreso' => 'required|date',
            'area' => 'required|string|max:50',
            'estado' => 'string|max:10|nullable', // Puede ser nulo ya que tiene un valor por defecto en la base de datos
        ]);

        // Encuentra el empleado a actualizar
        $employee = Employee::findOrFail($id);
        
        // Actualizamos los campos del empleado
        $employee->primer_apellido = $request->input('primer_apellido');
        $employee->segundo_apellido = $request->input('segundo_apellido');
        $employee->primer_nombre = $request->input('primer_nombre');
        $employee->otros_nombres = $request->input('otros_nombres');
        $employee->tipo_identificacion = $request->input('tipo_identificacion');
        $employee->numero_identificacion = $request->input('numero_identificacion');
        $employee->pais_empleo = $request->input('pais_empleo');
        $employee->fecha_ingreso = $request->input('fecha_ingreso');
        $employee->area = $request->input('area');
        $employee->estado = $request->input('estado', 'Activo'); // Si no se proporciona, se establece en 'Activo' por defecto
        
        // Si se modifican los nombres y/o apellidos, se re-genera el correo electrónico
        if ($employee->isDirty('primer_apellido') || $employee->isDirty('segundo_apellido') || $employee->isDirty('primer_nombre') || $employee->isDirty('otros_nombres')) {
            $employee->correo_electronico = $this->generateEmailAddress($employee);
        }

        // Guardamos la fecha de edición
        $employee->updated_at = now();

        // Guardamos los cambios en la base de datos
        $employee->save();

        // Retornamos una respuesta de éxito
        $data = [
            'message' => 'El empleado ha sido actualizado correctamente',
            'employee' => $employee
        ];
        return response()->json($data);
    }

    // Función para generar un nuevo correo electrónico basado en los nombres y apellidos del empleado
    private function generateEmailAddress($employee)
    {
        $nombre = Str::lower($employee->primer_nombre);
        $apellido = Str::lower($employee->primer_apellido);
        $dominio = '@example.com'; // Reemplaza example.com con el dominio deseado
        return $nombre . '.' . $apellido . $dominio;
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
        $employee->delete();
        $data = [
            'message' => 'El empleado ha sido eliminado correctamente',
            'employee' => $employee
        ];
        return response()->json($data);
    }
}
