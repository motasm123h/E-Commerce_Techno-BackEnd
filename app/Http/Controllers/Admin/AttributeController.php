<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * جلب جميع الخصائص لعرضها في الجدول
     */
    public function index()
    {
        // جلب الخصائص مرتبة من الأحدث للأقدم
        $attributes = Attribute::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $attributes
        ]);
    }

    /**
     * إضافة خاصية جديدة إلى قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|string|in:checkbox,select' // التأكد من نوع الحقل
        ]);

        $attribute = Attribute::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Attribute created successfully',
            'data' => $attribute
        ], 201);
    }

    /**
     * تعديل خاصية موجودة
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validatedData = $request->validate([
            // تجاهل الاسم الحالي أثناء التحقق من التكرار (unique)
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'type' => 'required|string|in:checkbox,select'
        ]);

        $attribute->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Attribute updated successfully',
            'data' => $attribute
        ]);
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully'
        ]);
    }
}
