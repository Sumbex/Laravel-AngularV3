import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';
import { post } from 'selenium-webdriver/http';
import { Anios } from 'src/app/modelos/anios.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-actas',
  templateUrl: './actas.component.html',
  styleUrls: ['./actas.component.css']
})
export class ActasComponent implements OnInit {

  selectAnio: Anios[] = [];
  idAnioActual;
  suc_res1=false;
  valorAnio: Anios = {
    descripcion: ''
  };
  anio_load=true;

  cabeza:string;
  modal_tabla = null;
  public url: string;

  miembros;
  tabla='';
  resumen_anterior='';
  dirigente;
  fecha='';
  hora='';
  ubicacion='';
  rendicion_cuentas='';
  rendicion_camping='';
  rendicion_bienestar='';
  rendicion_directorio='';
  ayuda_aporte='';
  socios_nuevos='';
  varios='';

  puntos_a_tratar='';

  total_socios='';
  total_asistidos='';
  total_justificados='';
  sin_justificar='';
  caracter="";
  caracterizar = false;

  // actualizar actas
  desarrollo_asamblea_id='';
  e_caracter = '';
  e_hora = '';
  e_fecha = '';
  e_ubicacion = '';
  e_tabla = '';
  e_resumen_anterior = '';
  e_rendicion_cuentas = '';
  e_rendicion_camping = '';
  e_rendicion_bienestar = '';
  e_rendicion_directorio = '';
  e_ayuda_aporte = '';
  e_socios_nuevos = '';
  e_varios = '';
  e_total_socios = '';
  e_total_asistidos = '';
  e_total_justificados = '';
  e_sin_justificar = '';
  e_puntos_a_tratar='';
  guardar_load=false;


  actas;

  token = localStorage.getItem('token').replace(/['"]+/g, '');
  constructor(private modalService2: NgbModal, public _http: HttpClient, private _fechasService: AniosService,) {

    this.url = global.url;

   }

  ngOnInit() {
    console.log(document.getElementById("cabeza")); 
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

   myfunction() {
     var kkck = <HTMLElement>document.getElementById('cabeza');
     //var elem = foo.target;
     //var TextInsideLi = elem[0].innerHTML;

     console.log(kkck.textContent);
  }
  cargar_select() {

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response;
        this.valorAnio.descripcion = this.idAnioActual.id;
        this.suc_res1 = true;

        // this.listo_para_listar(this.suc_res1, this.suc_res2);
        this.listar(this.suc_res1, this.valorAnio.descripcion);
      },
      error => {
        console.log(error);
      }
    )

  }
  modaltabla(modal) {
    //var passwordVacio = <HTMLInputElement>document.getElementById('password');
    //passwordVacio.value ='';

    
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
    this.guardar_load=true;
    console.log("nada o que");
    const formData = new FormData();
    formData.append('tabla', this.tabla);
    formData.append('resumen_anterior', this.resumen_anterior);
    formData.append('fecha', this.fecha);
    formData.append('hora', this.hora);
    formData.append('ubicacion', this.ubicacion);
    formData.append('rendicion_cuentas', this.rendicion_cuentas);
    formData.append('rendicion_camping', this.rendicion_camping);
    formData.append('rendicion_bienestar', this.rendicion_bienestar);
    formData.append('rendicion_directorio', this.rendicion_directorio);
    formData.append('ayuda_aporte', this.ayuda_aporte);
    formData.append('socios_nuevos', this.socios_nuevos);
    formData.append('varios', this.varios);
    formData.append('total_socios', this.total_socios);
    formData.append('total_asistidos', this.total_asistidos);
    formData.append('total_justificados', this.total_justificados);
    formData.append('sin_justificar', this.sin_justificar);
    formData.append('tipo', this.caracter);


    this._http.post(this.url + "registrar_acta", formData, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data', 'mensaje' }) => {
      //console.log(val.estado);
      if (val.estado == 'success') {
        this.actas = val.data;
        this.guardar_load = false;
        alert("Formulario ingresado con exito!");
       this.limpiar();
      }
      if(val.estado == 'failed'){
        this.guardar_load = false;
        alert(val.mensaje)
      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });





  }

  enviar2() {
    this.guardar_load = true;
    const formData = new FormData();
    formData.append('tabla', this.tabla);
    formData.append('puntos_a_tratar', this.puntos_a_tratar);
    formData.append('fecha', this.fecha);
    formData.append('hora', this.hora);
    formData.append('ubicacion', this.ubicacion);
   

    formData.append('total_socios', this.total_socios);
    formData.append('total_asistidos', this.total_asistidos);
    formData.append('total_justificados', this.total_justificados);
    formData.append('sin_justificar', this.sin_justificar);
    formData.append('tipo', this.caracter);


    this._http.post(this.url + "registrar_acta", formData, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data','mensaje' }) => {
      //console.log(val.estado);
      if (val.estado == 'success') {
        this.actas = val.data;
        this.guardar_load = false;
        alert("Formulario ingresado con exito!");
        this.limpiar();
      }
      if (val.estado == 'failed') {
        this.guardar_load = false;
        alert(val.mensaje);
        
      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });

  }


  actualizar(){
        const formData = new FormData();
        formData.append('desarrollo_asamblea_id', this.desarrollo_asamblea_id);
        formData.append('tabla', this.e_tabla);
        formData.append('resumen_anterior', this.e_resumen_anterior);
        formData.append('fecha', this.e_fecha);
        formData.append('hora', this.e_hora);
        formData.append('ubicacion', this.e_ubicacion);
        formData.append('rendicion_cuentas', this.e_rendicion_cuentas);
        formData.append('rendicion_camping', this.e_rendicion_camping);
        formData.append('rendicion_bienestar', this.e_rendicion_bienestar);
        formData.append('rendicion_directorio', this.e_rendicion_directorio);
        formData.append('ayuda_aporte', this.e_ayuda_aporte);
        formData.append('socios_nuevos', this.e_socios_nuevos);
        formData.append('varios', this.e_varios);
        formData.append('total_socios', this.e_total_socios);
        formData.append('total_asistidos', this.e_total_asistidos);
        formData.append('total_justificados', this.e_total_justificados);
        formData.append('sin_justificar', this.e_sin_justificar);
        formData.append('tipo', this.e_caracter);

        this._http.post(this.url + "actualizar_acta", formData, {
          headers: new HttpHeaders(
            {
              'Authorization': 'Bearer' + this.token,
              //'Content-Type': 'application/form-data'
            }
          )
        }).subscribe((val: { 'estado', 'data', 'mensaje' }) => {
          //console.log(val.estado);
          if (val.estado == 'success') {
            
            alert(val.mensaje);
            this.listar(true, this.valorAnio.descripcion);
            this.limpiar();
          }
          if (val.estado == 'failed') {
            alert(val.mensaje)
          }
        }, response => {
          console.log("POST call in error", response);
        },
          () => {
            console.log("The POST observable is now completed.");
          });
  }

  actualizar2() {
    const formData = new FormData();
    formData.append('desarrollo_asamblea_id', this.desarrollo_asamblea_id);
    formData.append('tabla', this.e_tabla);
    formData.append('puntos_a_tratar', this.e_puntos_a_tratar);
    formData.append('fecha', this.e_fecha);
    formData.append('hora', this.e_hora);
    formData.append('ubicacion', this.e_ubicacion);
    
    formData.append('total_socios', this.e_total_socios);
    formData.append('total_asistidos', this.e_total_asistidos);
    formData.append('total_justificados', this.e_total_justificados);
    formData.append('sin_justificar', this.e_sin_justificar);
    formData.append('tipo', this.e_caracter);

    this._http.post(this.url + "actualizar_acta2", formData, {
      headers: new HttpHeaders(
        {
          'Authorization': 'Bearer' + this.token,
          //'Content-Type': 'application/form-data'
        }
      )
    }).subscribe((val: { 'estado', 'data', 'mensaje' }) => {
      //console.log(val.estado);
      if (val.estado == 'success') {

        alert(val.mensaje);
        this.listar(true, this.valorAnio.descripcion);
        this.limpiar();
      }
      if (val.estado == 'failed') {
        alert(val.mensaje)
      }
    }, response => {
      console.log("POST call in error", response);
    },
      () => {
        console.log("The POST observable is now completed.");
      });
  }


  listar(res1, anio){
    if (res1 == true) {
      this.anio_load = true;
        this._http.get(this.url + "listar_acta/"+anio, {
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
              this.anio_load = false;
            console.log(this.anio_load);

          }
          if (val.estado == "false") {
            this.anio_load = false;
          }
        }, response => {
          console.log("POST call in error", response);
        },
          () => {
            console.log("The POST observable is now completed.");
          });
     }
  }

  changeAnio(evento) {
    // this.valorAnio.descripcion = evento.target.value;
    console.log(this.valorAnio)
    this.listar(true, this.valorAnio.descripcion);
  }

  limpiar(){
    this.miembros = '';
    this.tabla = '';
    this.resumen_anterior = '';
    this.dirigente = '';
    this.fecha = '';
    this.hora = '';
    this.ubicacion = '';
    this.rendicion_cuentas = '';
    this.rendicion_camping = '';

    this.rendicion_bienestar = '';
    this.rendicion_directorio = '';
    this.ayuda_aporte = '';
    this.socios_nuevos = '';

    this.total_socios = '';
    this.total_asistidos = '';
    this.total_justificados = '';
    this.sin_justificar = '';
    this.varios = '';
    this.puntos_a_tratar = '';
  }

  // select_caracter(){
  //   // console.log(this.caracter);
  //   if (this.caracter == "ordinaria"){
  //     this.caracterizar
  //   }
  //   if (this.caracter == "extraordinaria") {

  //   }
  // }

}
