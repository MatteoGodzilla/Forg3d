time_limit = "always"
load_analyitcs();

units = {
    "always":"year",
    "year":"month",
    "month":"week",
    "week":"day"
}

const alwaysOption = document.querySelector("input[value='always']");
const YearOption = document.querySelector("input[value='year']");
const MonthOption = document.querySelector("input[value='month']");
const WeekOption = document.querySelector("input[value='week']");

alwaysOption.onclick = () => {
    time_limit = "always"
    load_analyitcs();
}

YearOption.onclick = () => {
    time_limit = "year"
    load_analyitcs();
}

MonthOption.onclick = () => {
    time_limit = "month"
    load_analyitcs();
}

WeekOption.onclick = () => {
    time_limit = "week"
    load_analyitcs();
}


function load_analyitcs(){
    fetch(`/api/getAnalytics.php?limit=`+time_limit)
            .then(res => res.json())
            .then(obj => {
                const analyticsDiv = document.getElementById("stats");
                analyticsDiv.innerHTML = '';
                for(const [key,value] of Object.entries(obj)){
                    const p = document.createElement('p');
                    p.textContent = `${key} ${value}`;
                    analyticsDiv.appendChild(p)
                }
                if(analyticsDiv.innerHTML == ''){
                    analyticsDiv.innerHTML = "<p>Nessuna statistica da mostrare</p>"
                }
            });

    fetch(`/api/getAnalyticsGraphs.php?limit=`+time_limit)
            .then(res => res.json())
            .then(obj => {
                const analyticsDiv = document.getElementById("graphs");
                analyticsDiv.innerHTML = '';
                for(const [key,arr] of Object.entries(obj)){
                    const labels = obj[key].map(item => item[units[time_limit]]);
                    const sales = obj[key].map(item => parseFloat(item.Tot));
                    console.log(sales);
                    console.log(labels);
                    const p = document.createElement('h3');
                    p.textContent = `${key}`
                    analyticsDiv.appendChild(p);

                    //generate graph
                    canvas = document.createElement('canvas');
                    new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                            label: key,
                            data: sales,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                display: true,
                                text: 'Euro (€)'
                                }
                            },
                            x: {
                                title: {
                                display: true,
                                text: units[time_limit]
                                }
                            }
                            }
                        }
                    });
                    analyticsDiv.appendChild(canvas);
                }
                if(analyticsDiv.innerHTML == ''){
                    analyticsDiv.innerHTML = "<p>Nessun grafico da mostrare</p>"
                }
            });
}

