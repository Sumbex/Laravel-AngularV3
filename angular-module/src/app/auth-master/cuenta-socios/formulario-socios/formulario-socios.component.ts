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
  foto:object;
  rut:string;
  fNacimiento:string;
  nombre:string;
  aPaterno:string;
  aMaterno:string;

  constructor(public _http:HttpClient) {
  this.url = global.url;
   }

  ngOnInit() {
  }

  onSubmit(data)
  {
    console.log(data)
    /*this._http.post(this.url + "cambiar_password", formData,{headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
      }
    )}).subscribe((val : {'estado','mensaje'} ) => {
        //console.log(val.estado);
      }, response => {
          console.log("POST call in error", response);
      },
      () => {
        console.log("The POST observable is now completed.");
    });*/
  }

}
