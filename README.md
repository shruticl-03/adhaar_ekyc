# adhaar_ekyc
This project is a simple web-based system for Aadhaar-based Paperless EKYC (Electronic Know Your Customer) verification. It enables users to request an OTP for Aadhaar authentication and subsequently submit the OTP to retrieve Aadhaar details securely. The application processes the data using APIs and dynamically displays the output, including Aadhaar holder details and profile image, in a user-friendly form.

Features
Request OTP: Users can enter their Aadhaar number to generate an OTP.
Submit OTP: Enter the OTP and transaction ID to fetch Aadhaar details.
Dynamic Data Display: Outputs data in a neatly formatted form, including text fields and the Aadhaar profile image.
Responsive UI: A clean and minimalistic interface that adapts to different screen sizes.
API Integration: Uses backend APIs for OTP generation and Aadhaar details retrieval.
Secure Handling: Ensures sensitive data is securely transmitted and displayed.

Technologies Used
Frontend: HTML, CSS, JavaScript
Backend: PHP
API Requests: Handled via sendRequest function for secure communication.
Styling: Modern, responsive design with minimal dependencies.
