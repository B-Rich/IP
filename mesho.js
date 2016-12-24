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
		for(var ii = 0; ii < formInputs.length; ii++){
			formInputs[ii].disabled = true;
		}
    } else {
        ansDiv.style.display = 'none';
    }
	setTimeout(function(){
	var ajax = new XMLHttpRequest();
	ajax.open("POST","takeTest.php",true);
	ajax.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
            console.log(this.responseText);
		}
	};
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("jack=25");
	document.getElementsByName("ans")[0].value = " ";
	},1);
}

function loadFile(ans){
	var ajax = new XMLHttpRequest();
	ajax.open("GET","takeTest?corrAns="+ans,true);
	ajax.send();
	ajax.onreadystatechange = function(){
		if(this.readystate == 4 && this.status == 200){
		}
	};
}