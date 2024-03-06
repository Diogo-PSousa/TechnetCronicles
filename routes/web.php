<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StaticsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UnblockAppealController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FavoritesController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Profile
Route::get('/user/{user_id}', [UserController::class, 'showProfile'])->Middleware('auth');
Route::patch('/user/updateUsername', [UserController::class, 'updateUsername'])->Middleware('auth');
Route::patch('/user/updateBio', [UserController::class, 'updateBio'])->Middleware('auth');
Route::get('/user/{bio}', [UserController::class, 'showProfile'])->Middleware('auth');
Route::delete('/user/{user_id}/delete', [UserController::class, 'deleteSelf'])->middleware('check.role');
Route::post('/file/upload', [FileController::class, 'upload'])->middleware('check.role');

//Admin
Route::delete('/admin/delete/{user_id}', [AdminController::class, 'deleteUser'])
    ->middleware('admin');
    Route::delete('/admin/deletecomment/{comment_id}', [AdminController::class, 'deleteComment'])
    ->name('admin.deletecomment') 
    ->middleware('admin');
    Route::delete('/admin/deletearticle/{article_id}', [AdminController::class, 'deleteArticle'])
    ->name('admin.deletearticle') 
    ->middleware('admin');
Route::get('/admin', [AdminController::class, 'listUsers'])->middleware('admin');
Route::patch('/admin/swapAdmin/{user_id}', [AdminController::class, 'swapAdmin'])
    ->middleware('admin');
Route::patch('/admin/swapBlock/{user_id}', [AdminController::class, 'swapBlock'])
    ->middleware('admin');
Route::get('/admin/topicProposals', [AdminController::class, 'showTopicProposals'])->middleware('admin');
Route::delete('/admin/topicProposals/delete/{tag_id}', [AdminController::class, 'deleteTag'])
    ->middleware('admin');
Route::patch('/admin/topicProposals/accept/{tag_id}', [AdminController::class, 'acceptTag'])
    ->middleware('admin');
Route::get('/admin/unblockAppeals', [UnblockAppealController::class, 'showUnblockAppeals'])->middleware('admin');
Route::delete('/admin/unblockAppeals/delete/{appeal_id}', [AdminController::class, 'deleteAppeal'])
    ->middleware('admin');
Route::patch('/admin/unblockAppeals/accept/{appeal_id}', [AdminController::class, 'acceptAppeal'])
    ->middleware('admin');
Route::get('/admin/manageReports', [AdminController::class, 'manageReports'])->middleware('admin');
Route::delete('/admin/ignoreReportUser/{report_id}', [AdminController::class, 'ignoreReportUser'])
    ->middleware('admin');
Route::delete('/admin/ignoreReportComment/{report_id}', [AdminController::class, 'ignoreReportComment'])
    ->middleware('admin');
Route::delete('/admin/ignoreReportArticle/{report_id}', [AdminController::class, 'ignoreReportArticle'])
    ->middleware('admin');
Route::patch('/admin/respondToBlock/{userId}', [AdminController::class, 'respondToBlock'])
    ->name('admin.respondToBlock')
    ->middleware('admin');


Route::post('/createTag', [TagController::class, 'createTag'])->Middleware('auth');

//Blocked
Route::get('/blocked', function () {
    return view('pages.blocked');
});
Route::post('/blocked/appeal', [UnblockAppealController::class, 'unblockAppeal'])->Middleware('auth');

// Home
Route::redirect('/', '/news');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// Recover Password

Route::controller(ForgetPasswordController::class)->group(function (){
    Route::get('/forgetPassword', 'forgetPassword')->name('forget.password');
    Route::post('/forgetPassword', 'forgetPasswordPost')->name('forget.password.post');
    Route::get('/resetPassword/{token}', 'resetPassword')->name('reset.password');
    Route::post('/resetPassword', 'resetPasswordPost')->name('reset.password.post');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Search

Route::controller(SearchController::class)->group(function () {
    Route::post('/search', 'searchResults')->name('search');
});

// Reports

// Reporting routes
Route::post('/report/user/{user_id}', 'ReportController@reportuser')->name('report.user')->middleware('auth');
Route::post('/report/article/{article_id}', 'ReportController@reportarticle')->name('report.article')->middleware('auth');
Route::post('/report/comment/{comment_id}', 'ReportController@reportcomment')->name('report.comment')->middleware('auth');


Route::controller(NewsArticleController::class)->group(function () {
    Route::get('/news', 'index')->name('news.index');
    Route::get('/news/{article:newsarticle_id}', 'show')->name('news.post');
    Route::get('/news/{article:newsarticle_id}/edit', 'edit')->name('news.post.edit');
    Route::patch('/news/{article:newsarticle_id}/edit', 'update')->name('news.post.update');
    Route::get('/write', 'create')->name('news.post.write');
    Route::post('/write', 'store')->name('news.post.store');
    Route::delete('/news/{article:newsarticle_id}', 'delete')->name('news.post.delete');
    Route::post('/news/{article:newsarticle_id}/vote/{voteType}', 'vote')->name('article.vote')->Middleware('auth');
});

Route::get('/news/followed', 'NewsArticleController@followed')->name('news.followed');


Route::resource('tags', 'TagController');
Route::resource('newstags', 'NewsTagController');

Route::get('/news/tag/{tag}', 'NewsArticleController@showByTag')->name('news.tag');


Route::get('user/{user}', 'UserController@profile')->name('user.profile')->middleware('check.role');

Route::get('/popular-topics', 'TagController@popularTopics')->name('popular.topics')->middleware('check.role');

Route::post('/follow/{username}', 'UserController@followUser')->name('follow.user')->middleware('auth')->middleware('check.role');
Route::delete('/unfollow/{username}', 'UserController@unfollowUser')->name('unfollow.user')->middleware('auth')->middleware('check.role');

Route::post('/tag/follow/{tagName}', 'TagController@followTag')->name('follow.tag')->middleware('check.role');
Route::delete('/tag/unfollow/{tagName}', 'TagController@unfollowTag')->name('unfollow.tag')->middleware('check.role');


Route::post('/comments/add', [CommentController::class, 'store'])->name('comments.store')->middleware('check.role');
Route::delete('/comments/delete/{commentId}', [CommentController::class, 'deleteComment'])->name('comments.delete')->middleware('check.role');
Route::patch('/comments/update/{id}', [CommentController::class, 'updateComment'])->name('comments.update')->middleware('check.role');
Route::get('/comments/partials/{comment}', [CommentController::class, 'getCommentPartial'])->name('comments.partials');
Route::get('/comments/sortByDate/{order?}', [CommentController::class, 'sortByDate'])->name('comments.sortByDate');
Route::post('/comment/{comment:comment_id}/vote/{voteType}', [CommentController::class, 'vote'])->name('comment.vote')->Middleware('auth')->middleware('check.role');

Route::get('/about-us', [StaticsController::class, 'about'])->name('about-us');
Route::get('/faq', [StaticsController::class, 'faq'])->name('faq');
Route::get('/contacts', [StaticsController::class, 'contacts'])->name('contacts');
Route::post('/send-email', [StaticsController::class, 'sendEmail'])->name('send.email');

Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');

Route::post('/file/upload', [FileController::class, 'upload']);

Route::post('/toggle-favorite/{article}', [FavoritesController::class, 'toggleFavorite'])->name('toggle.favorite');

