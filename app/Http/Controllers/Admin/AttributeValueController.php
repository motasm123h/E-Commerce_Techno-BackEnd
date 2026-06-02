<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    
    public function getValuesByAttribute(Attribute $attribute)
    {
        return response()->json([
            'success' => true,
            'data' => $attribute->values()->get()
        ]);
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255'
        ]);

        // منع تكرار نفس القيمة لنفس المواصفة
        $exists = AttributeValue::where('attribute_id', $validatedData['attribute_id'])
            ->where('value', $validatedData['value'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'This value already exists for this attribute.'], 422);
        }

        $value = AttributeValue::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Value added successfully',
            'data' => $value
        ], 201);
    }

   
    public function update(Request $request, AttributeValue $attributeValue)
    {
        $validatedData = $request->validate([
            'value' => 'required|string|max:255'
        ]);

        $attributeValue->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Value updated successfully',
            'data' => $attributeValue
        ]);
    }

    /**
     * حذف قيمة
     */
    public function destroy(AttributeValue $attributeValue)
    {
        $attributeValue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Value deleted successfully'
        ]);
    }
}