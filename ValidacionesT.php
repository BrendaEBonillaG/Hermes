<?php

// Capturar los datos enviados desde el formulario
$cardNumber = str_replace(' ', '', $_POST['card-number']); // Eliminar espacios de la tarjeta
$expiryDate = $_POST['expiry-month-year'];
$cvv = $_POST['cvv'];

//elimina cualquier carácter no numérico
$expiryDate = preg_replace('/\D/', '', $expiryDate);

if (strlen($expiryDate) === 4) {

    $expiryMonth = substr($expiryDate, 0, 2);
    $expiryYear = substr($expiryDate, 2, 2);
} else {
    // Manejar error: longitud incorrecta
    echo "<script>
            
            window.location.href = '../Alumno/invalido.php';
          </script>";
    exit;
}

// Validación algoritmo de Luhn
function luhnCheck($cardNumber) {
    $sum = 0;
    $shouldDouble = false;

    // Recorre el número de tarjeta de derecha a izquierda
    for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
        $digit = intval($cardNumber[$i]);

        if ($shouldDouble) {
            // Duplica el dígito
            $digit *= 2;
            // Si el resultado es mayor que 9, resta 9
            if ($digit > 9) {
                $digit -= 9;
            }
        }

        $sum += $digit;
        $shouldDouble = !$shouldDouble;  // Alterna el valor de shouldDouble
    }

    // El número es válido si la suma es múltiplo de 10
    return ($sum % 10) === 0;
}

// Validación longitud y tipo de tarjeta
function validateCardLength($cardNumber) {
    $visaRegex = '/^4[0-9]{12}(?:[0-9]{3})?$/';
    $masterCardRegex = '/^5[1-5][0-9]{14}$/';
    $amexRegex = '/^3[47][0-9]{13}$/';

    if (preg_match($visaRegex, $cardNumber)) {
        return "Visa";
    } elseif (preg_match($masterCardRegex, $cardNumber)) {
        return "MasterCard";
    } elseif (preg_match($amexRegex, $cardNumber)) {
        return "Amex";
    } else {
        return "Invalid";
    }
}

// Validación fecha de expiración
function validateExpiryDate($expiryMonth, $expiryYear) {
    $now = new DateTime();
    $currentMonth = $now->format('m');  // Mes actual
    $currentYear = $now->format('y');   // Año actual (2 dígitos)

    // Convertir las entradas en números
    $month = intval($expiryMonth);
    $year = intval($expiryYear);

    if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
        return false; // La tarjeta está vencida
    }

    // Meses deben estar entre 1 y 12
    if ($month < 1 || $month > 12) {
        return false; // Fecha no válida
    }

    return true;
}

// Validación CVV
function validateCVV($cvv, $cardType) {
    if ($cardType === "Amex") {
        $cvvRegex = '/^[0-9]{4}$/';
    } else {
        $cvvRegex = '/^[0-9]{3}$/';
    }

    return preg_match($cvvRegex, $cvv);
}

// Validación final
if (
    luhnCheck($cardNumber) &&
    validateCardLength($cardNumber) !== "Invalid" &&
    validateExpiryDate($expiryMonth, $expiryYear) &&
    validateCVV($cvv, validateCardLength($cardNumber))
) {
    echo "valido";
} else {
    echo "invalido";
}

?>
