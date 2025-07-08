const alwaysOption = document.querySelector("input[value='always']");
const YearOption = document.querySelector("input[value='year']");
const MonthOption = document.querySelector("input[value='month']");
const WeekOption = document.querySelector("input[value='week']");

const units = {
    "always":"year",
    "year":"month",
    "month":"week",
    "week":"day"
}

let time_limit = "always"

//ui bindings

alwaysOption.onclick = () => {
    time_limit = "always"
    set_chart_colors();
    load_analyitcs();
}

YearOption.onclick = () => {
    time_limit = "year"
    set_chart_colors();
    load_analyitcs();
}

MonthOption.onclick = () => {
    time_limit = "month"
    set_chart_colors();
    load_analyitcs();
}

WeekOption.onclick = () => {
    time_limit = "week"
    set_chart_colors();
    load_analyitcs();
}

themeToggle.addEventListener("click", () => { 
    set_chart_colors()
    load_analyitcs();
});

// actual functions

function set_chart_colors(){
    const style = window.getComputedStyle(document.body) 
    Chart.defaults.backgroundColor = style.getPropertyValue("--background");
    //Chart.defaults.borderColor = style.getPropertyValue("--text");
    Chart.defaults.color = style.getPropertyValue("--text");
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
                    //const p = document.createElement('h3');
                    //p.textContent = `${key}`
                    //analyticsDiv.appendChild(p);

                    //generate graph
                    canvas = document.createElement('canvas');
                    new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: key,
                                data: sales,
                                borderColor: '#36A2EB',
                                backgroundColor: '#9BD0F5',
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

function init(){
    set_chart_colors();
    load_analyitcs();
}
init()
