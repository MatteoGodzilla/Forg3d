time_limit = "always"
load_analyitcs();

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
            })
}