const toggleReportButton = document.querySelector("#toggleReportForm");
const reportForm = document.querySelector("form.hidden2");

toggleReportButton.onclick = () => {
    if(reportForm.classList.contains("hidden2")){
        reportForm.classList.remove("hidden2");
    } else {
        reportForm.classList.add("hidden2");
    }
}
