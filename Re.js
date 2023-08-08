function validateForm() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;
    var phone = document.getElementById("phone").value;
    var state = document.getElementById("state").value;
    var district = document.getElementById("district").value;
    var subDistrict = document.getElementById("sub-district").value;
    var houseNo = document.getElementById("H.no").value;

    if (!username || !password || !confirmPassword || !phone || !state || !district || !subDistrict || !houseNo) {
        alert("กรุณากรอกข้อมูลส่วนนี้!!");
        return false;
    }

    if (password !== confirmPassword) {
        alert("รหัสผ่านไม่ตรงกัน!!");
        return false;
    }

    return true;
}
