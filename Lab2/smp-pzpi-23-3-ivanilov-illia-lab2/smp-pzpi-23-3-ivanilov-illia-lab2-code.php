<?php

function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT) {
    $chars = preg_split('//u', $input, -1, PREG_SPLIT_NO_EMPTY);
    $input_length = count($chars);
    
    $padding_needed = $pad_length - $input_length;
    
    if ($padding_needed <= 0) {
        return $input;
    }
    
    $padding = str_repeat($pad_string, $padding_needed);
    
    switch ($pad_type) {
        case STR_PAD_LEFT:
            return $padding . $input;
        case STR_PAD_BOTH:
            $left_pad = floor($padding_needed / 2);
            $right_pad = $padding_needed - $left_pad;
            return str_repeat($pad_string, $left_pad) . $input . str_repeat($pad_string, $right_pad);
        case STR_PAD_RIGHT:
        default:
            return $input . $padding;
    }
}

$products = [
    1 => ['Молоко пастеризоване', 12],
    2 => ['Хліб чорний', 9],
    3 => ['Сир білий', 21],
    4 => ['Сметана 20%', 25],
    5 => ['Кефір 1%', 19],
    6 => ['Вода газована', 18],
    7 => ['Печиво "Весна"', 14],
];

$cart = [];
$username = '';
$age = 0;

function showMenu() {
    echo "################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function showProducts($products) {
    echo mb_str_pad("№", 4) . mb_str_pad("НАЗВА", 30) . mb_str_pad("ЦІНА", 5) . "\n";
    foreach ($products as $id => $item) {
        echo mb_str_pad($id, 4) . mb_str_pad($item[0], 30) . mb_str_pad($item[1], 5) . "\n";
    }
    echo mb_str_pad("", 4) . str_repeat("-", 30) . "\n";
    echo mb_str_pad(0, 4) . "ПОВЕРНУТИСЯ\n";
    echo "Виберіть товар: ";
}

function showCart($cart) {
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
    } else {
        echo "У КОШИКУ:\n";
        echo mb_str_pad("НАЗВА", 30) . mb_str_pad("КІЛЬКІСТЬ", 10) . "\n";
        foreach ($cart as $name => $qty) {
            echo mb_str_pad($name, 30) . mb_str_pad($qty, 10) . "\n";
        }
    }
}

function showInvoice($cart, $products) {
    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ. Немає товарів для оплати.\n";
        return;
    }

    echo mb_str_pad("№", 4) . mb_str_pad("НАЗВА", 30) . mb_str_pad("ЦІНА", 6) . mb_str_pad("КІЛЬКІСТЬ", 10) . mb_str_pad("ВАРТІСТЬ", 10) . "\n";
    $i = 1;
    $total = 0;
    foreach ($products as $id => [$name, $price]) {
        if (isset($cart[$name])) {
            $qty = $cart[$name];
            $cost = $price * $qty;
            echo mb_str_pad($i++, 4) . mb_str_pad($name, 30) . mb_str_pad($price, 6) . mb_str_pad($qty, 10) . mb_str_pad($cost, 10) . "\n";
            $total += $cost;
        }
    }
    echo "РАЗОМ ДО CПЛАТИ: $total\n";
}

function configureProfile(&$username, &$age) {
    do {
        echo "Ваше імʼя: ";
        $username = trim(fgets(STDIN));
        if (!preg_match('/\pL/u', $username)) {
            echo "ПОМИЛКА! Імʼя повинно містити хоча б одну літеру.\n";
        }
    } while (!preg_match('/\pL/u', $username));

    do {
        echo "Ваш вік: ";
        $ageInput = trim(fgets(STDIN));
        $age = (int)$ageInput;
        if ($age < 7 || $age > 150) {
            echo "ПОМИЛКА! Вік повинен бути від 7 до 150 років.\n";
        }
    } while ($age < 7 || $age > 150);

    echo "Профіль оновлено: $username, $age років\n";
}

while (true) {
    showMenu();
    $cmd = trim(fgets(STDIN));

    switch ($cmd) {
        case '1':
            while (true) {
                showProducts($products);
                $selection = trim(fgets(STDIN));
                if ($selection === '0') break;

                if (!isset($products[(int)$selection])) {
                    echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
                    continue;
                }

                $productName = $products[(int)$selection][0];
                echo "Вибрано: $productName\n";
                echo "Введіть кількість, штук: ";
                $qty = (int)trim(fgets(STDIN));

                if ($qty < 0 || $qty >= 100) {
                    echo "ПОМИЛКА! Кількість повинна бути від 0 до 99\n";
                    continue;
                }

                if ($qty === 0) {
                    unset($cart[$productName]);
                    echo "ВИДАЛЯЮ З КОШИКА\n";
                } else {
                    $cart[$productName] = $qty;
                }

                showCart($cart);
            }
            break;

        case '2':
            showInvoice($cart, $products);
            break;

        case '3':
            configureProfile($username, $age);
            break;

        case '0':
            echo "Дякуємо за покупки!\n";
            exit;

        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
            break;
    }
}
