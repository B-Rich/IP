<?php
function addQuestion($questFile,$newQuest){
    /*$availQuests = file_get_contents($questFile);
    $availQuests .= "\n".$newQuest;
    file_put_contents($questFile, $availQuests);*/
    file_put_contents($questFile, "\n".$newQuest, FILE_APPEND | LOCK_EX);
}

function prepareQuestion($chosenQuest){
    $questBuffer = explode(" # ", $chosenQuest); //.split(" # ");
    $quest = $questBuffer[0];
    print($quest."\n");
    $potAns = explode(";", $questBuffer[1]); //.split(" ");

    foreach ($potAns as &$ans)
        print($ans."\n");

    $corrAns = $questBuffer[2];
    print($corrAns."\n");
}

$qFileName = "D:\ip-proj\questions.txt";
$qBankBuffer = fopen($qFileName, "rw") or die("Unable to open file!");
$qBank = preg_split('/$\R?^/m', fread($qBankBuffer,filesize("D:\ip-proj\questions.txt")));
addQuestion($qFileName,"How are you? # Good;Bad;Fuck you;Aight # Fuck you");
echo $qBank[0];
echo "\n";

$q1 = "Where does Egypt lie? # America Europe Africa Asia # Africa";
$buffer = explode(" # ", $q1);;//.split(" # ");
$quest = $buffer[0];
print($quest."\n");
$ans = explode(";", $buffer[1]);//.split(" ");

foreach ($ans as &$an)
    print($an."\n");

$cAns = $buffer[2];
print($cAns."\n");

print("Choose answer"."\n");
//$choice = input()
$uAns = $ans[0];//$ans[int($choice)-1];
print($uAns."\n");
if ($uAns == $cAns){
    print("Correct");
}
else{
    print("Incorrect");
}
echo "\n prepareQuestion function test: \n";
prepareQuestion($qBank[0]);
fclose($qBankBuffer);