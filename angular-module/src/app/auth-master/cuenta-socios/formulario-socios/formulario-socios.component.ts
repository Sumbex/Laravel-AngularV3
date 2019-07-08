import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';

@Component({
  selector: 'app-formulario-socios',
  templateUrl: './formulario-socios.component.html',
  styleUrls: ['./formulario-socios.component.css']
})
export class FormularioSociosComponent implements OnInit 
{

  url:string;
  selectedImage:File;

  foto:File;
  rut:string;
  fecha_nacimiento:string;
  nombres:string;
  a_paterno:string;
  a_materno:string;

  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http:HttpClient) {
  this.url = global.url;
   }

  ngOnInit() {
  }

  onSelectImage(event) {
    //console.log(event.srcElement.files[0])

     this.selectedImage = event.srcElement.files[0];
      //console.log(this.selectedImage);
  }


  onSubmit(data)
  {
<<<<<<< HEAD
    
    const form = new FormData();
    form.append('foto', this.selectedImage);
    form.append('rut',this.rut);
    form.append('fecha_nacimiento', this.fecha_nacimiento);
    form.append('nombres',this.nombres);
    form.append('a_paterno', this.a_paterno);
    form.append('a_materno',this.a_materno);
    console.log(form);
    this._http.post(this.url + "ingresar_socio",form,{ headers: new HttpHeaders(
=======
    this.foto=data;
    const formData = new FormData();
    formData.append('foto',this.foto);
    console.log(this.foto);
    this._http.post(this.url + "ingresar_socio",formData,{headers: new HttpHeaders(
>>>>>>> bryanv
      {
          'Authorization': 'Bearer' + this.token,
          // 'Content-Type': 'multipart/form-data'
      }
    )}).subscribe((val : {'estado','mensaje'} ) => {

      }, response => {
          console.log("POST call in error", response);
      },
      () => {
        console.log("The POST observable is now completed.");
    });
  }

}
