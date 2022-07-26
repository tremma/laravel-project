<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;
use App\UserInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller{

	private $request;

	 public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

	public function registrForm() {

		return view('registr');
	}

	public function registrationPostHandler(){


		$this->request->validate([
            'email' => 'required|email:rfc|unique:users|min:6',
            'password' => 'required|min:6|max:25'
        ]);

            $newUser = User::create(['email' => $this->request->email, 'password' => Hash::make($this->request->password)]);
        // UsersInfo::create(['user_id' => $newUser->id, 'job_title' => '', 'phone' => '', 'address' => '']);
        // UsersLinks::create(['user_id' => $newUser->id, 'vk' => '', 'telegram' => '', 'instagram' => '']);

              return redirect()->route('login');

	}

	public function home(){

		$status = session('status');
        session()->forget('status');

		// $users = DB::table('users')->get();

		 $users = DB::table('users')
            ->join('user_info', 'users.id', '=', 'user_info.user_id')
            // ->paginate(6)
            ->get();
         
		return view('home',['users' => $users, 'status' => $status]);

	}


	public function loginForm(){

		return view('login');
	}

	  public function loginPostHandler()
    {
        $this->request->validate([
            'email' => 'required|email:rfc',
            'password' => 'required'
        ]);
        
        $credentials = $this->request->only('email', 'password');

        if($this->request->remember == "on"){
            $is_remembered = true;
        } else {
            $is_remembered = false;
        }

        if(Auth::attempt($credentials, $is_remembered)){
            $this->request->session()->regenerate();
            $this->request->session(['status' => 'User logged in successfully!']);
            return redirect()->route('home');

        }

        return redirect()->back()->withErrors([
            'email' => 'Email or password are invalid.',
            ]);
    }

    public function logout()
    {
        Auth::logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return redirect()->route('home');

    }

     public function createShowForm()
    {    
        return view('create');
    }

        public function createPostHandler()
    {
        //валидация 
        $this->request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:rfc|unique:users|min:6',
            'password' => 'required|min:6|max:25',
            'job_title' => 'required|min:3',
            'phone' => 'required|min:6',
            'address' => 'required|min:6',
            'avatar' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000'
      
        ]);
        
    	
        $newUser = User::create([
                'name' => $this->request->name,
                'email' => $this->request->email, 
                'password' => Hash::make($this->request->password)
            ]);

        UserInfo::create([
            'user_id' => $newUser->id, 
            'job_title' => $this->request->job_title, 
            'phone' => $this->request->phone, 
            'address' => $this->request->address,
            'status' => $this->request->status,
            'avatar' => $this->request->file('avatar')->store('uploads')
        ]);

      
        
      
        session(['status' => 'New user registered!']);
        
        return redirect()->route('home');
    }

    public function editShowForm($id = null){

    	//проверка есть ли такой id
    	if (!$id){
            session(['status' => 'User ID does not exist!']);
            return redirect()->route('home');
        }

        //получаем ользователя по id
        $userById = DB::table('users')
        ->join('user_info', function ($join) {
            $join->on('users.id', '=', 'user_info.user_id');
        })
        ->where('users.id', $id)
        ->get();

        //проверка кто редактирует
        if (!(auth()->user()->id == $userById->first()->user_id || (auth()->check() && auth()->user()->is_admin == 1))){
            session(['status' => 'You do not have permission to edit user profile']);
            return redirect()->route('home');
        }
        
        
        // если находит такого юзера
        if ($userById->first()) {
            
            return view('edit', ['user' => $userById->first()]);
        } 

        // если нет то редиректим назад с ошибкой
        session(['status' => 'User ID does not exist']);
        return redirect()->route('home');

    }

     public function editPostHandler($id)
    {
        //валидация данных формы
        $this->request->validate([
            'name' => 'required|min:2',
            'job_title' => 'required|min:3',
            'phone' => 'required|min:6',
            'address' => 'required|min:6',
        ]);
        

        // меняем поле нэйм в таблице users 
        DB::table('users')
              ->where('id', $id)
              ->update(['name' => $this->request->name]);

        // переписываем поля в таблице user_info       
        DB::table('user_info')
              ->where('user_id', $id)
              ->update([
                  'job_title' => $this->request->job_title,
                  'phone' => $this->request->phone,
                  'address' => $this->request->address,
                ]);
        
        // возвращаем назад с сообщением об успешном редактировании пользователя
        session(['status' => 'User data updated successfully!']);
        return redirect()->route('home');
        
    }

      public function securityShowForm($id = null)
    {
        //проверяем существование id
        if (!$id){
            session(['status' => 'User ID does not exist!']);
            return redirect()->route('home');
        }

        //получаем данные пользователя для вывода в форме
        $userById = DB::table('users')->where('id', $id)->get();

        //проверяем, редактирует пользователь свой профиль или это делает админ
        if (!(auth()->user()->id == $userById->first()->id || (auth()->check() && auth()->user()->is_admin == 1))){
            session(['status' => 'You do not have permission to edit user profile']);
            return redirect()->route('home');
        }
        
        $userById = DB::table('users')->where('id', $id);
        
        if($userById->first()){
            return view('security', ['user' => $userById->first()]);
        }

        session(['status' => 'User ID does not exist']);
        return redirect()->route('home');
    }

    public function securityPostHandler($id = null)
    {
        //проверяем существование id
        if (!$id){
            session(['status' => 'User ID does not exist!']);
            return redirect()->route('home');
        }
        
        //берем существующий email
        $old_email = DB::table('users')->where('id', $id)->first()->email;

        if($old_email != $this->request->email){//сравниваем email из формы и email в базе
            //валидация данных формы если email был измененн
            $this->request->validate([
                'email' => 'required|email:rfc|unique:users|min:6',
                'password' => 'required|min:6|max:25',
                'password_again' => 'required|same:password'
            ]);    
        }else{
            //валидация данных формы если email не был измененн
            $this->request->validate([
                'email' => 'required|email:rfc|min:6',
                'password' => 'required|min:6|max:25',
                'password_again' => 'required|same:password'
            ]);    
        }

        //обновляем данные пользователя
        $result = DB::table('users')
              ->where('id', $id)
              ->updateOrInsert(['email' => $this->request->email, 'password' => Hash::make($this->request->password)]);

        //если обновление прошло успешно, то выводим статус
        if($result){
            session(['status' => 'Security data updated successfully!']);
            return redirect()->route('home');
        }

    }

    public function avatarShowForm($id = null)
    {
        //проверяем существование id
        if (!$id){
            session(['status' => 'User ID does not exist!']);
            return redirect()->route('home');
        }

        //получаем данные пользователя для вывода в форме
        $userById = DB::table('users')->where('id', $id)->get();

        //проверяем, редактирует пользователь свой профиль или это делает админ
        if (!(auth()->user()->id == $userById->first()->id || (auth()->check() && auth()->user()->is_admin == 1))){
            session(['status' => 'You do not have permission to edit user profile']);
            return redirect()->route('home');
        }
        
        $user = DB::table('user_info')->where('user_id', $id)->first();

        return view('avatar', ['user' => $user]);
    }



    public function avatarPostHandler($id = null)
    {
        //проверяем существование id
        if (!$id){
            session(['status' => 'User ID does not exist!']);
            return redirect()->route('home');
        }
        
        //валидация данных формы
        $this->request->validate([
            'avatar' => 'required|image'
        ]);


        //удаляем старый аватар
        $imageName = DB::table('user_info')
                    ->where('user_id', $id)
                    ->select('*')
                    ->first()
                    ->avatar;

        Storage::delete($imageName);

        //обновляем данные пользователя
        $result = DB::table('user_info')
              ->where('user_id', $id)
              ->update(['avatar' => $this->request->file('avatar')->store('uploads')]);

        if($result){
                session(['status' => 'Avatar updated successfully!']);
                return redirect()->route('home');
            }

    }

}