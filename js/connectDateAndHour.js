
    document.querySelector("form").addEventListener("submit", function (e) {
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;

    if (!date || !time) {
    alert("Wybierz datę i godzinę.");
    e.preventDefault();
    return;
}

    document.getElementById("booking_time").value = `${date} ${time}`;
});
