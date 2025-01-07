<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("sendRequest.php");

    $json_data = file_get_contents('php://input');
    $request_data = json_decode($json_data, true);

    if ($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "1") {
        $url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_get_otp";
        $body = [
            "aadharNo" => $request_data['aadhaarNumber'],
            "transId" => "12345",
            "docType" => 211,
        ];
        $decrypted = sendRequest($url, $body);

        echo json_encode($decrypted);
        exit;
    }

    if ($request_data['type'] == "Aadhaar-paperless-EKYC" && $request_data['step'] == "2") {
        $url = "https://www.truthscreen.com/v1/apicall/nid/aadhar_submit_otp";
        $body = [
            "transId" => $request_data['tsTransId'],
            "otp" => (int)$request_data['otp']
        ];
        $decrypted = sendRequest($url, $body);

        echo json_encode($decrypted);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar EKYC</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: maroon;
            padding-top: 50px;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 50px;
            left: 100px;
            width: 200px;
            height: auto;
        }

        .container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin-top: 80px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .output {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f8ff;
            border: 1px solid #007bff;
            border-radius: 4px;
            text-align: left;
            font-family: monospace;
            white-space: pre-wrap;
            color: #333;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .output h2 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #007bff;
        }

        .output form {
            display: grid;
            gap: 10px;
        }

        .output label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .output input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Improved form field alignment for otpSubmitOutput */
        .otpSubmitForm {
            display: grid;
            gap: 15px;
            grid-template-columns: 1fr; /* Single column layout */
            align-items: center;
            margin-top: 20px;
        }

        .otpSubmitForm label {
            text-align: left;
            font-weight: normal;
        }

        .otpSubmitForm input {
            text-align: center;
        }

        .otpSubmitForm button {
            margin-top: 10px;
            width: auto;
            padding: 8px 16px;
        }

        .aadhaar-image {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
    <script>
        async function requestOTP(event) {
            event.preventDefault();

            const aadhaarNumber = document.getElementById('aadhaarNumber').value;
            const requestData = {
                type: "Aadhaar-paperless-EKYC",
                step: "1",
                aadhaarNumber: aadhaarNumber
            };

            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });
                const result = await response.json();
                displayOutput("OTP Request Response", result, 'otpOutput');
            } catch (error) {
                displayOutput("Error", { message: "Error requesting OTP", details: error.message }, 'otpOutput');
            }
        }

        async function submitOTP(event) {
            event.preventDefault();

            const transId = document.getElementById('transId').value;
            const otp = document.getElementById('otp').value;
            const requestData = {
                type: "Aadhaar-paperless-EKYC",
                step: "2",
                tsTransId: transId,
                otp: otp
            };

            try {
                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });
                const result = await response.json();
                displayOutput("OTP Submission Response", result, 'otpSubmitOutput');
            } catch (error) {
                displayOutput("Error", { message: "Error submitting OTP", details: error.message }, 'otpSubmitOutput');
            }
        }

        function displayOutput(title, data, outputId) {
            const outputDiv = document.getElementById(outputId);
            outputDiv.innerHTML = `
                <h2>${title}</h2>
                ${jsonToForm(data, outputId)}
            `;
        }

        function jsonToForm(json, outputId) {
            let form = "<form class='otpSubmitForm'>";
            
            // Function to recursively handle nested objects
            function processObject(data, prefix = '') {
                Object.keys(data).forEach(key => {
                    let fieldKey = prefix ? prefix + '.' + key : key;
                    if (typeof data[key] === 'object' && data[key] !== null) {
                        form += `<fieldset><legend>${fieldKey}</legend>`;
                        processObject(data[key], fieldKey);  // Recursively process the nested object
                        form += `</fieldset>`;
                    } else {
                        if (key === 'Image') {  // Check if the key is 'Image'
                            form += `
                                <label for="${fieldKey}">Aadhaar Image</label>
                                <img src="${data[key]}" alt="Aadhaar Image" class="aadhaar-image">
                            `;
                        } else {
                            form += `
                                <label for="${fieldKey}">${key}</label>
                                <input type="text" id="${fieldKey}" name="${fieldKey}" value="${data[key]}" readonly>
                            `;
                        }
                    }
                });
            }

            processObject(json);  // Start the recursive process
            form += "</form>";
            return form;
        }
    </script>
</head>
<body>
    <img src="logo3.png" alt="Logo" class="logo">

    <div class="container">
        <h1>Aadhaar EKYC</h1>

        <!-- Request OTP Form -->
        <form id="requestOtpForm" onsubmit="requestOTP(event)">
            <label for="aadhaarNumber">Aadhaar Number</label>
            <input type="text" id="aadhaarNumber" name="aadhaarNumber" required>
            <button type="submit">Request OTP</button>
        </form>

        <!-- Output Section for OTP Request -->
        <div id="otpOutput" class="output"></div>

        <!-- Submit OTP Form -->
        <form id="submitOtpForm" onsubmit="submitOTP(event)">
            <label for="transId">Transaction ID</label>
            <input type="text" id="transId" name="transId" required>
            <label for="otp">OTP</label>
            <input type="text" id="otp" name="otp" required>
            <button type="submit">Submit OTP</button>
        </form>

        <!-- Output Section for OTP Submission -->
        <div id="otpSubmitOutput" class="output"></div>
    </div>
</body>
</html>
