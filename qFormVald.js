function checkQForm() {
    var q = document.forms["qForm"]["question"].value;
    var cA = document.forms["qForm"]["corrAns"].value;
    var c1 = document.forms["qForm"]["choice1"].value;
    var c2 = document.forms["qForm"]["choice2"].value;
    var c3 = document.forms["qForm"]["choice3"].value;
    var c4 = document.forms["qForm"]["choice4"].value;
    var qF = true;
    var cF = true;
    var cAF = true;
    if (q == "") {
        alert("Question must be filled out");
        qF = false;
    }
    if (c1 == "" || c2 == "" || c3 == "" || c4 == "") {
        alert("Please fill out all the answers");
        cF = false;
    }
    if (cA == "") {
        alert("Please choose the correct answer");
        cAF = false;
    }
    alert("Question successfully added");
    return cAF && cF && qF;
}
function checkQAForm() {
    var ans = document.forms["qaForm"]["ans"].value;
    if (ans == "") {
        alert("Please choose an answer");
        return false;
    }
}

function showAnswer() {
    var formInputs = document.forms[0];
    var showAns = document.getElementById('showAns');
    var ansDiv = document.getElementById('ansDiv');
    //if (ansDiv.style.display === 'none') {
    if (showAns.checked) {
        ansDiv.style.display = 'block';
        for (var ii = 0; ii < formInputs.length; ii++) {
            formInputs[ii].disabled = true;
        }
    } else {
        ansDiv.style.display = 'none';
    }
    setTimeout(function () {
        var ajax = new XMLHttpRequest();
        ajax.open("POST", "takeTest", true);
        ajax.setRequestHeader("Content-type", "application/json");
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var js = JSON.parse(this.responseText);
                console.log(js.ans);
                console.log("FUCK");
            }
        };
        var JSON_DATA = JSON.stringify({"ans": "empty"});
        console.log(JSON_DATA);
        console.log("FUCK");
        ajax.send(JSON_DATA);
    }, 2000);
}

function loadFile(ans) {
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "takeTest?corrAns=" + ans, true);
    ajax.send();
    ajax.onreadystatechange = function () {
        if (this.readystate == 4 && this.status == 200) {
            var js = JSON.parse(ajax.responseText);
            console.log(js.ans);
        }
    };
}

function showAnswer2() {
    var formInputs = document.forms[0];
    var labels = document.getElementsByTagName('label');
    var showAns = document.getElementById('showAns');
    var ansDiv = document.getElementById('ansDiv');
    var ansBuff = ansDiv.getElementsByTagName('p')[0].innerHTML;
    var corrAns = ansBuff.replace('Correct: ', '');
    for (var ix = 0; ix < labels.length; ix++) {
        if (labels[ix].innerHTML.trim() == corrAns) {
            labels[ix].style.color = 'green';
            labels[ix].style.fontWeight = 'bold';
        } else {
            labels[ix].style.color = 'red';
        }
    }
    if (showAns.checked) {
        ansDiv.style.display = 'block';
        for (var ii = 0; ii < formInputs.length; ii++) {
            formInputs[ii].disabled = true;
        }
    } else {
        ansDiv.style.display = 'none';
    }
    setTimeout(function () {
        var ajax = new XMLHttpRequest();
        ajax.open("POST", "takeTest", true);
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                //console.log(this.responseText);
                document.body.innerHTML = this.responseText;
            }
        };
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("ans=25");
    }, 2000);
}

function xamTimer(period, time) {
    var c = document.getElementById("timercanvas");
    var ctx = c.getContext("2d");
    ctx.restore()
    console.log('xamTimer')
    if (!isNaN(period)) {
        var timer = period, mins, sec;
        var interVal = setInterval(function () {
            mins = parseInt(timer / 60, 10);
            sec = parseInt(timer % 60, 10);
            mins = mins < 10 ? "0" + mins : mins;
            sec = sec < 10 ? "0" + sec : sec;
            ctx.fillText(time, 15, 50);
            $(time).html(mins + "m : " + sec + "s");
            if (--timer < 0) {
                timer = period;
                $("#time").empty();
                clearInterval(interVal)
                SubmitFunction();
            }
        }, 1000);
    }
}
function saveTimer() {
    ctx.save();
    console.log('Saved');
}


function SubmitFunction() {
    saveTimer();
    $("form").submit();
    /*var ajax = new XMLHttpRequest();
     ajax.open("POST", "takeTest", true);
     ajax.onreadystatechange = function () {
     if (this.readyState == 4 && this.status == 200) {
     //console.log(this.responseText);
     document.body.innerHTML = this.responseText;
     }
     };
     ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
     ajax.send("ans=25");*/
}
