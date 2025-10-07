<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
 public function destroy($id)
    {
        $error = \App\Models\Error::find($id);
        if ($error) {
            $error->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
