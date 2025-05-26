let current = 0;
activeImage(current);

function activeImage(n){
    let immagini = document.getElementsByClassName("image");
    for(let i =0; i< immagini.length;i++){
        immagini[i].style.display="none";
    }
    if(n<0){
        n = immagini.length-1;
    }
    else if(n>=immagini.length){
        n = 0;
    }
    immagini[n].style.display="block"; 
}

function previousImage(){
    current--;
    activeImage(current);
}

function nextImage(){
    current++;
    activeImage(current);
}