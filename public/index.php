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
            width: 300px;
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
            <label for="amount">Amount (USD)</label>
            <input type="number" id="amount" required>
        </div>
        <div id="card-element"><!--Stripe.js injects the Card Element--></div>
        <button id="submit">Pay</button>
        <div id="error-message"></div>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('<?php echo getenv("STRIPE_PUBLISHABLE_KEY"); ?>');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const amount = document.getElementById('amount').value;

            try {
                const response = await fetch('/process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ amount: amount })
                });

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                const clientSecret = data.clientSecret;

                const { error } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                    },
                });

                if (error) {
                    document.getElementById('error-message').textContent = error.message;
                } else {
                    document.getElementById('error-message').textContent = 'Payment successful!';
                }
            } catch (error) {
                document.getElementById('error-message').textContent = 'Failed to process payment: ' + error.message;
            }
        });
    </script>
</body>
</html>
