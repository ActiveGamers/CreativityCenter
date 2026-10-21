<?php

namespace App\Http\Controllers;

use App\Actions\SubmitContactMessage as SubmitContactMessageAction;
use App\Http\Requests\StoreContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Show the contact page with the school information.
     */
    public function index(): View
    {
        return view('pages.contact', [
            'school' => config('school'),
        ]);
    }

    /**
     * Store a contact message.
     */
    public function store(
        StoreContactMessage $request,
        SubmitContactMessageAction $action,
    ): JsonResponse|RedirectResponse {
        $action->handle($request->validated());

        $message = 'پیام شما با موفقیت ثبت شد. کادر آموزشی در اولین فرصت کاری پاسخ می‌دهد.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
