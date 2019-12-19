import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';
import { post } from 'selenium-webdriver/http';

@Component({
  selector: 'app-actas',
  templateUrl: './actas.component.html',
  styleUrls: ['./actas.component.css']
})
export class ActasComponent implements OnInit {

  cabeza:string;
  modal_tabla = null;
  public url: string;

  miembros;
  orden_dia;
  detalle_orden_dia;
  dirigente;
  fecha;
  hora;
  ubicacion;


  actas;

  token = localStorage.getItem('token').replace(/['"]+/g, '');
  constructor(private modalService2: NgbModal, public _http: HttpClient) {

    this.url = global.url;

   }

  ngOnInit() {
    console.log(document.getElementById("cabeza")); 
  }

   myfunction() {
     var kkck = <HTMLElement>document.getElementById('cabeza');
     //var elem = foo.target;
     //var TextInsideLi = elem[0].innerHTML;

     console.log(kkck.textContent);
  }

  modaltabla(modal) {
    //var passwordVacio = <HTMLInputElement>document.getElementById('password');
    //passwordVacio.value ='';

    this.listar();
    this.modal_tabla = this.modalService2.open(modal, { size: 'xl' });

  }

  modal_detalle(modal){
    this.modal_tabla = this.modalService2.open(modal, { size: 'xl' });
  }









//PETICIONES

  post(route, formData){
  return this._http.post(this.url + ""+route+"", formData, {
    headers: new HttpHeaders(
      {
        'Authorization': 'Bearer' + this.token,
        //'Content-Type': 'application/form-data'
      }
    )
  })
}

  enviar() {

    console.log("nada o que");
    const formData = new FormData();
    formData.append('miembros', this.miembros);
    formData.append('orden_dia', this.orden_dia);
    formData.append('detalle_orden_dia', this.detalle_orden_dia);
    formData.append('dirigente', this.dirigente);
    formData.append('fecha', this.fecha);
    formData.append('hora', this.hora);
    formData.append('ubicacion', this.ubicacion);


    this._http.post(this.url + "registrar_acta", formData, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data' }) => {
      //console.log(val.estado);
      if (val.estado == 'success') {
        this.actas = val.data;
        alert("Acta ingresada con exito");
       this.limpiar();
      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });





  }


  listar(){

    this._http.get(this.url + "listar_acta", {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data' }) => {
      console.log(val);
      if (val.estado == "success") {
          this.actas = val.data;

      }
      if (val.estado == "false") {

      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });
  }

  limpiar(){
    this.miembros = '';
    this.orden_dia = '';
    this.detalle_orden_dia = '';
    this.dirigente = '';
    this.fecha = '';
    this.hora = '';
    this.ubicacion = '';
  }

}
