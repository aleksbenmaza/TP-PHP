<?php
/**
 * Created by PhpStorm.
 * User: alexandremasanes
 * Date: 03/11/2017
 * Time: 11:53
 */

define('CSV_FILE', 'users.csv');

spl_autoload_register(function(string $class_name) : void {
    require $class_name . '.php';
});

function csv_parse(string $file_name, array & $properties = []) : array {
    $csvFile = file($file_name);
    $data = [];
    $properties = [];
    foreach (str_getcsv($csvFile[0]) as $property)
        $properties[] = $property;

    unset($csvFile[0]);

    foreach ($csvFile as $key => $line) {
        $entry = (object)[];
        $line = str_getcsv($line);
        foreach ($properties as $key2 => $property)
            $entry->$property = $line[$key2];
        $data[] = $entry;
    }
    return $data;
}

session_start();

$_SESSION['logged'] = $_SESSION['logged'] ?? FALSE;

$request = new ServletRequest(
    $_SERVER['REQUEST_METHOD'],
    headers_list(),
    $_SERVER['REQUEST_URI'],
    (object) $_GET,
    (object) $_POST,
    $_SESSION
);

$servlet1 = new class('/') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response): void {
        if(!$request->getSession()['logged']) {
            $response->setStatus(401);
            $response->render('login');
        } else {
            $response->setAttributes([
                'users' => csv_parse(CSV_FILE)
            ]);
            $response->render('users');
        }
    }

    public function doPost(ServletRequest $request, ServletResponse $response): void {
        $response->setStatus(405);
    }
};

$servlet2 = new class('/users/login') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response): void {
        $response->setStatus(405);
    }

    public function doPost(ServletRequest $request, ServletResponse $response): void {
        if(!$request->getSession()['logged']) {
            foreach(csv_parse(CSV_FILE) as $user) {
                if (
                    $user->user_name == $request->getRequestData()->user_name &&
                    $user->password == $request->getRequestData()->password
                )
                    $request->getSession()['logged'] = TRUE;
            }
            $response->sendRedirect('/');
        } else
            $response->setStatus(403);
    }
};

$servlet6 = new class('/users/logout') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response): void {
        $response->setStatus(405);
    }

    public function doPost(ServletRequest $request, ServletResponse $response): void {
        if($request->getSession()['logged']) {
            $request->getSession()['logged'] = FALSE;
            $response->sendRedirect('/');
        } else
            $response->setStatus(403);
    }
};

$servlet3 = new class('/users/add') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response): void {
        $response->setStatus(405);
    }

    public function doPost(ServletRequest $request, ServletResponse $response): void {
        if(!$request->getSession()['logged']) {
            $response->setStatus(403);

        } else {
            $handle = fopen(CSV_FILE, 'a');
            fputcsv($handle, array_values((array)$request->getRequestData()));
            fclose($handle);
            $response->sendRedirect('/');
        }
    }
};

$servlet4 = new class('/users/delete') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response) : void {
        $response->setStatus(405);
    }

    public function doPost(ServletRequest $request, ServletResponse $response) : void {
        if(!$request->getSession()['logged']) {
            $response->setStatus(403);
        } else {
            $properties = [];
            $users = csv_parse(CSV_FILE, $properties);
            $handle = fopen(CSV_FILE, 'w');
            $to_keep = [];

            foreach($users as $key => $user)
                if(!in_array($key, $request->getRequestData()->keys))
                    $to_keep[] = array_values((array) $user);

            $new_entries = [$properties] + $to_keep;
            array_walk($new_entries, function($value) use($handle) : void {
                fputcsv($handle, $value);
            });
            fclose($handle);
            $response->sendRedirect('/');
        }
    }
};

$servlet5 = new class('/users/update') extends Servlet {
    public function doGet(ServletRequest $request, ServletResponse $response): void {
        $response->setStatus(405);
    }

    public function doPost(ServletRequest $request, ServletResponse $response): void {
        if(!$request->getSession()['logged']) {
            $response->render('login');
        } else
            $response->setStatus(403);
    }
};

$router = new Router(
    $servlet1,
    $servlet2,
    $servlet3,
    $servlet4,
    $servlet5,
    $servlet6
);

$router->dispatch($request);

$router->output();


foreach ($request->getSession() as $key => $value)
    $_SESSION[$key] = $value;