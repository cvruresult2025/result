<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CVRU Result</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: url('https://cvrubihar.ac.in/img/16f5e925a74a199a0822e08139a2e74c') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: url('https://cvrubihar.ac.in/img/16f5e925a74a199a0822e08139a2e74c') no-repeat center center fixed;
      background-size: cover;
      filter: blur(8px); 
      z-index: -1;
    }
        
    .container {
      background: rgba(255, 255, 255, 0.85);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      text-align: center;
      max-width:350px;
      width: 100%;
    }
    h2 {
      color: #004080;
    }
    input[type="text"] {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background-color: #004080;
      color: white;
      padding: 10px 20px;
      margin: 10px 5px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .hidden {
      display: none;
    }
    .message {
      margin-top: 15px;
      font-size: 16px;
    }
    .ftr{
      font-size: 9px:
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="cvrulogo.jpg" alt="Logo" style="width: 100%; max-width: 100%; margin-bottom: 15px;" />
    <h2>Check your Result</h2>
    <input type="text" id="rollNo" placeholder="Enter Roll Number" />
    <input type="text" id="studentName" placeholder="Enter Student Name" />
    <div>
      <button id="checkBtn" onclick="checkResult()">Check Result</button>
      <button id="resetBtn" class="hidden" onclick="resetForm()">Check Another Result</button>
    </div>
    <div class="message" id="message"></div>
    <div id="resultBox" class="hidden"></div>
  </div>
<footer style="
  position: absolute;
  bottom: 10px;
  width: 100%;
  text-align: center;
  color: white;
  font-size: 14px;
  font-family: Arial, sans-serif;
  text-shadow: 1px 1px 2px black;
">
  This page is developed and maintained by the <strong>Department of CSE</strong>.
</footer>
  <script>
    let studentData = [];

    async function loadExcelData() {
      const response = await fetch("students.xlsx");
      const arrayBuffer = await response.arrayBuffer();
      const data = new Uint8Array(arrayBuffer);
      const workbook = XLSX.read(data, { type: "array" });
      const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
      studentData = XLSX.utils.sheet_to_json(firstSheet);
    }

    async function checkResult() {
      if (studentData.length === 0) {
        await loadExcelData();
      }
      const rollNo = document.getElementById("rollNo").value.trim();
      const studentName = document.getElementById("studentName").value.trim().toUpperCase();
      const message = document.getElementById("message");
      const resultBox = document.getElementById("resultBox");
      const checkBtn = document.getElementById("checkBtn");
      const resetBtn = document.getElementById("resetBtn");

      const student = studentData.find(
        s => String(s.RollNo).trim() === rollNo && String(s.StudentName).trim().toUpperCase() === studentName
      );

      if (student) {
        message.textContent = "";
  
        const headerMessage = student.Status === 'PASS'
          ? `<h2><span>🎉</span><span style="background: linear-gradient(90deg, #ff6a00, #ee0979, #8e2de2, #4a00e0); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">Congratulations!</span></h2>`
          : '<h2>🤔Contact Your Department</h2>';

        resultBox.innerHTML = `
        ${headerMessage}
          <p><strong>Roll No:</strong> ${student.RollNo}</p>
          <p><strong>Name:</strong> ${student.StudentName}</p>
          <p><strong>CGPA:</strong> ${student.CGPA}</p>
          <p><strong>Status:</strong> 
            <span style="color: ${student.Status === 'PASS' ? 'green' : 'red'}">${student.Status}</span>
          </p>
      `;
  
        resultBox.classList.remove("hidden");
        checkBtn.classList.add("hidden");
        resetBtn.classList.remove("hidden");
      }     
      else {
        resultBox.classList.add("hidden");
        message.innerHTML = "❌ Invalid details! Please try again.";
      }
    }

    function resetForm() {
      document.getElementById("rollNo").value = "";
      document.getElementById("studentName").value = "";
      document.getElementById("message").textContent = "";
      document.getElementById("resultBox").classList.add("hidden");
      document.getElementById("checkBtn").classList.remove("hidden");
      document.getElementById("resetBtn").classList.add("hidden");
    }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
</body>
</html>
