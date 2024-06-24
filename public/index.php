<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Payment Page</title>
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
        <div id="card-element"><!--Stripe.js injects the Card Element--></div>
        <button id="submit">Pay</button>
        <div id="error-message"></div>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const publishableKey = 'pk_test_51OEbBSEXbbIxpm4PY1x3VvwQz6GRjIQa5pjAbIpgD51H7RVxWhYUt94o3ApCzop0VXGxzmltXO7ceJ8KIYHprzqk00wGJzaAUU';
            console.log("Stripe Publishable Key:", publishableKey); // Debugging line

            const stripe = Stripe(publishableKey);
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const address = document.getElementById('address').value;
                const city = document.getElementById('city').value;
                const state = document.getElementById('state').value;
                const zip = document.getElementById('zip').value;
                const amount = document.getElementById('amount').value;

                try {
                    const response = await fetch('/process_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name, email, address, city, state, zip, amount })
                    });

                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    const clientSecret = data.clientSecret;

                    const { error } = await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: name,
                                email: email,
                                address: {
                                    line1: address,
                                    city: city,
                                    state: state,
                                    postal_code: zip,
                                },
                            }
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
        });
    </script>
</body>
</html>
