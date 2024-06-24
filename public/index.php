<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Virtual Terminal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
            margin: 0;
        }
        form {
            background: white;
            padding: 2em;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        #card-element {
            margin-bottom: 1em;
        }
        button {
            background: #6772e5;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        #error-message {
            color: red;
            margin-top: 1em;
        }
        .form-field {
            margin-bottom: 1em;
        }
    </style>
</head>
<body>
    <form id="payment-form">
        <div class="form-field">
            <label for="name">Name</label>
            <input type="text" id="name" required>
        </div>
        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" required>
        </div>
        <div class="form-field">
            <label for="address">Address</label>
            <input type="text" id="address" required>
        </div>
        <div class="form-field">
            <label for="city">City</label>
            <input type="text" id="city" required>
        </div>
        <div class="form-field">
            <label for="state">State</label>
            <input type="text" id="state" required>
        </div>
        <div class="form-field">
            <label for="zip">Zip Code</label>
            <input type="text" id="zip" required>
        </div>
        <div class="form-field">
            <label for="amount">Amount (USD)</label>
            <input type="number" id="amount" required>
        </div>
