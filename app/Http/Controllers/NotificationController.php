<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Get current user's notifications
     *
     * @param  [Illuminate\Http\Request] request
     * 
     * @OA\Get(path="/api/user/notifications",
     *   tags={"Notifications"},
     *   summary="Get user's notifications",
     *   description="",
     *   operationId="getUserNotifications",
     *   @OA\Response(
     *     response=200,
     *     description="User notifications",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Yelp error"
     *   )
     * )
     */        
    public function getUserNotifications(Request $request)
    {
        $userId = $request->user()['id'];
        $user = User::find($userId);
        
        return response()->json([
            'notifications' => $user->notifications()->get()
        ]);        
    }

    /**
     * Create a notifications 
     *
     * @param  [Illuminate\Http\Request] request
     * 
     * @OA\Post(path="/api/user/notifications",
     *   tags={"Notifications"},
     *   summary="Create a notifications",
     *   description="",
     *   operationId="createUserNotifications",
     *   @OA\Response(
     *     response=200,
     *     description="The new notification",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Yelp error"
     *   )
     * )
     */        
     public function createUserNotifications(Request $request)
    {
        $notificationData = request(['name', 'description']);
        global $user;

        try {
            $userId = $request->user()['id'];
            $user = User::findOrFail($userId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return response()->json(['message' => 'Invalid Logged User'], 404);
        }
        $notification = Notification::create([
            'name' => $notificationData['name'],
            'description' => $notificationData['description'],
            'user_id' => $userId
        ]);

        $notification->save();

        return response()->json($notification);
    }

    /**
     * Update a notification
     *
     * @param  [Illuminate\Http\Request] request
     * @param [integer] notificationId

     * @OA\Post(path="/api/user/notifications/{notificationId}",
     *   tags={"Notifications"},
     *   summary="Update a notifications",
     *   description="",
     *   operationId="updateUserNotifications",
     *   @OA\Parameter(
     *     name="notificationId",
     *     required=true,
     *     in="path",
     *     description="A notification Id",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="The new notification",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Yelp error"
     *   )
     * )
     */        

    public function updateUserNotifications(Request $request, $notificationId)
    {
        $notificationData = request(['name', 'description']);
        global $user, $notification;

        try {
            $userId = $request->user()['id'];
            $user = User::findOrFail($userId);
            $notification = Notification::findOrFail($notificationId);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return response()->json(['message' => 'Invalid notificationId'], 404);
        }

        $notification->name = $notificationData['name'];
        $notification->description = $notificationData['name'];

        $notification->save();

        return response()->json($notification);
    }

    /**
     * Delete a notification
     *
     * @param  [Illuminate\Http\Request] request
     * @param [integer] notificationId
     *
     * @OA\Delete(path="/api/user/notifications/{notificationId}",
     *   tags={"Notifications"},
     *   summary="Delete a notifications",
     *   description="",
     *   operationId="deleteUserNotifications",
     *   @OA\Parameter(
     *     name="notificationId",
     *     required=true,
     *     in="path",
     *     description="A notification Id",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Operation success",     
     *   ),
     *   @OA\Response(
     *     response=400, 
     *     description="Yelp error"
     *   )
     * )
     */        

    public function deleteUserNotifications(Request $request, $notificationId)
    {
        global $user, $notification;

        try {
            $userId = $request->user()['id'];
            $user = User::findOrFail($userId);
            $notification = Notification::findOrFail($notificationId);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return response()->json(['message' => 'Invalid notificationId'], 404);
        }

        Notification::destroy($notificationId);

        return response()->json([
            'message' => 'Notification '. $notificationId .' removed successfully'
        ]);
    }
    
}
