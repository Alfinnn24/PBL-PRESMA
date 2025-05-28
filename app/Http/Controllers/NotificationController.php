<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->User();
        // dd($notifications->toArray());
        if ($request->ajax()) {
            $notifications = $user->notifications;
            return DataTables::of($notifications)
                ->addColumn('judul', function ($row) {
                    return $row->data['title'] ?? 'Tidak ada judul';
                })
                ->addColumn('pesan', function ($row) {
                    return $row->data['message'] ?? 'Tidak ada pesan';
                })
                ->addColumn('linkTitle', function ($row) {
                    return $row->data['linkTitle'] ?? 'Lihat Detail';
                })
                ->addColumn('read', function ($row) {
                    return $row->read_at == null ? '1' : '0';
                })
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('link', function ($row) {
                    return isset($row->data['link']) ? url($row->data['link']) : '#';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : null;
                })
                ->rawColumns(['judul', 'pesan'])
                ->make(true);
        }
        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'list' => ['Home', 'Notifikasi']
        ];

        $page = (object) [
            'title' => 'Notifikasi'
        ];

        $activeMenu = 'notifikasi';

        return view('notifikasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        $link = $notification->data['link'] ?? '/';
        return redirect($link);
    }
    public function getUnreaded()
    {
        $notifications = auth()->user()->unreadNotifications;
        $data = [];

        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->id,
                'judul' => $notification->data['title'],
                'pesan' => $notification->data['message'],
                'linkTitle' => $notification->data['linkTitle'],
                'link' => url($notification->data['link']),
                'read' => $notification->read_at == null ? '1' : '0',
                'created_at' => $notification->created_at->format('Y-m-d H:i:s')
            ];
        }
        return response()->json(['data' => $data]);
    }

    public function readall()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true, 'message' => 'Semua notifikasi berhasil ditandai sebagai dibaca']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }
}
