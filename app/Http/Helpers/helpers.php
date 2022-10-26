<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (! function_exists('menuShow')) {
    function menuShow($routeName)
    {
        $class = 'show';
        
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('setMenuShow')) {
    function setMenuShow($menu)
    {
        $current_name = Route::currentRouteName();
        $exp = explode('.', $current_name);
        $name = $exp[0];

        $res = '';
        if (
            $menu['url'] == $name ||
            $menu['name'] == $name
        ) {
            $res = 'show';
        }
        return $res;
    }
}

if (!function_exists('setMenuActive')) {
    function setMenuActive()
    {
        $current_name = Route::currentRouteName();
        $exp = explode('.', $current_name);
        if (count($exp) > 1) {
            $name = $exp[1];
        } else {
            $name = $exp[0];
        }

        return $name;
    }
}

if (!function_exists('checkUserRole')) {
    function getUserRole($user)
    {
        $role = $user->role;
        $static_role = config('roles.static_role');
        
        $role_name = '';
        if ($role) {
            foreach ($static_role as $r) {
                if ($role == $r['id']) {
                    $role_name = $r['name'];
                }
            }
        }

        return $role_name;
    }
}

if (!function_exists('getUrlRedirect')) {
    function getUrlRedirect($role)
    {
        $url = '';
        if ($role == 'manager') {
            $url = 'hrd.dashboard';
        } else if ($role == 'hrd') {
            $url = 'hrd.dashboard';
        } else if ($role == 'finance') {
            $url = 'finance.dashboard';
        }

        return $url;
    }
}

if (!function_exists('setUserMenu')) {
    function setUserMenu($user)
    {
        $role = $user->role;
        $menus = Menu::all();
        $user_menus = [];
        $childs = [];
        $x = 0;
        foreach ($menus as $menu) {
            $role_menu = $menu->role;
            $explode = explode('|', $role_menu);
            for ($a = 0; $a < count($explode); $a++) {
                if ($menu->parent) {
                    $childs[$x] = $menu;
                }
                if (
                    $explode[$a] == $role &&
                    $menu->parent == null
                ) {
                    $user_menus[] = $menu;
                }
            }

            $x++;
        }
        $childs = array_values($childs);

        $menu = [];
        $a = 0;
        foreach ($user_menus as $user_menu) {
            $menu[$a] = [
                'name' => $user_menu->name,
                'icon' => $user_menu->icon,
                'url' => $user_menu->url,
                'child' => []
            ];

            $b = 0;
            foreach ($childs as $child) {
                if ($child->parent == $user_menu->id) {
                    $menu[$a]['child'][$b] = [
                        'name' => $child->name,
                        'icon' => $child->icon,
                        'url' => $child->url,
                    ];
                }
                $b++;
            }
            $a++;
        }
        Redis::del('menus');
        Redis::set('menus', json_encode($menu));
    }
}

if (!function_exists('setTitle')) {
    function setTitle($title)
    {
        Redis::set('page_title', $title);
    }
}

if (!function_exists('sendEmail')) {

    function sendEmail($data)
    {
        $subjects = [
            'register' => 'Pendaftaran Berhasil',
        ];

        $config = [
            'name' => 'HRIS System',
            'email' => 'gumilang.dev@gmail.com',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 465),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
        ];

        $mail = new PHPMailer(true);
        $html = view('email_template.register', $data)->render();

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $config['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['username'];
            $mail->Password   = $config['password'];
            if ($config['encryption'] == 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }else{
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $config['port'];
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom($config['email'], $config['name']);
            $mail->addAddress($data['receiver'], $data['receiver_name']);
            $mail->addReplyTo($config['email'], $config['name']);
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Subject';
            $mail->Body    = $html;
            $mail->send();
        } catch ( \Throwable $e) {
            return $e->getMessage();
        }

        return true;
    }
}