let current = 0;
activeImage(current);

function activeImage(){
    //console.log(current);
    let immagini = document.getElementsByClassName("image");
    if(immagini.length <= 0)
        return;
    for(let i = 0; i < immagini.length; i++){
        immagini[i].style.display="none";
    }
    if(current<0){
        current = immagini.length-1;
    }
    else if(current>=immagini.length){
        current = 0;
    }
    immagini[current].style.display="block"; 
}

function previousImage(){
    current--;
    activeImage();
}

function nextImage(){
    current++;
    activeImage();
}
