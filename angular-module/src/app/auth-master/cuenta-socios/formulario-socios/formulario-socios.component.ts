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

  foto:File;
  rut:string;
  fNacimiento:string;
  nombre:string;
  aPaterno:string;
  aMaterno:string;

  token = localStorage.getItem('token').replace(/['"]+/g, '');

  constructor(public _http:HttpClient) {
  this.url = global.url;
   }

  ngOnInit() {
  }

  onSubmit(data)
  {
    this.foto=data;
    const formData = new FormData();
    formData.append('foto',this.foto);
    console.log(this.foto);
    this._http.post(this.url + "ingresar_socio",formData,{headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          'Content-Type': 'multipart/form-data'
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
