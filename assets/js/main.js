var a=true;
function pass(){
    const name = document.getElementById('password');
    const cpassword = document.getElementById('password');
    // console.log("working")
    if(a){
      // console.log(a)
    
      // console.dir(name )
      name.removeAttribute("type")
      name.setAttribute("type","text")
      // document.getElementById('password').setAttribute("type'","password");
      // document.getElementById('eye').style.color='black';
      a=false;
    }
    else{
      name.removeAttribute("type")
      name.setAttribute("type","password")
      a=true;
    }
}
var b=true;
function cpass(){
    const name = document.getElementById('cpassword');
    // console.log("working")
    if(b){
      // console.log(a)
    
      // console.dir(name )
      name.removeAttribute("type")
      name.setAttribute("type","text")
      // document.getElementById('password').setAttribute("type'","password");
      // document.getElementById('eye').style.color='black';
      b=false;
    }
    else{
        console.log(name)
      name.removeAttribute("type")
      name.setAttribute("type","password")
      b=true;
    }
}
