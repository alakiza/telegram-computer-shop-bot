<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TelegramUser\BulkDestroyTelegramUser;
use App\Http\Requests\Admin\TelegramUser\DestroyTelegramUser;
use App\Http\Requests\Admin\TelegramUser\IndexTelegramUser;
use App\Http\Requests\Admin\TelegramUser\StoreTelegramUser;
use App\Http\Requests\Admin\TelegramUser\UpdateTelegramUser;
use App\Models\TelegramUser;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TelegramUsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTelegramUser $request
     * @return array|Factory|View
     */
    public function index(IndexTelegramUser $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(TelegramUser::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'dialog_path'],

            // set columns to searchIn
            ['id', 'dialog_path', 'dialog_params']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.telegram-user.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.telegram-user.create');

        return view('admin.telegram-user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTelegramUser $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTelegramUser $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the TelegramUser
        $telegramUser = TelegramUser::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/telegram-users'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/telegram-users');
    }

    /**
     * Display the specified resource.
     *
     * @param TelegramUser $telegramUser
     * @throws AuthorizationException
     * @return void
     */
    public function show(TelegramUser $telegramUser)
    {
        $this->authorize('admin.telegram-user.show', $telegramUser);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TelegramUser $telegramUser
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(TelegramUser $telegramUser)
    {
        $this->authorize('admin.telegram-user.edit', $telegramUser);


        return view('admin.telegram-user.edit', [
            'telegramUser' => $telegramUser,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTelegramUser $request
     * @param TelegramUser $telegramUser
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTelegramUser $request, TelegramUser $telegramUser)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values TelegramUser
        $telegramUser->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/telegram-users'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/telegram-users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTelegramUser $request
     * @param TelegramUser $telegramUser
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTelegramUser $request, TelegramUser $telegramUser)
    {
        $telegramUser->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTelegramUser $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTelegramUser $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    TelegramUser::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
