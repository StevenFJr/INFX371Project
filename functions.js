
    console.log("Testing");
    function updateNum(priorityInput,secondaryInput){
        console.log("Test");
        document.getElementById(secondaryInput)[0].value=document.getElementById(priorityInput)[0].value;
    }

    document.getElementById("MrkSld").addEventListener("change", function(){ updateNum("MrkSld","MrkNum"); });
    document.getElementsByName("MrkNum")[0].addEventListener(onchange, function(){ updateNum("MrkNum","MrkSld"); });
