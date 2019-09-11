import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { BienestarService } from '../../../servicios/bienestar.service';
import { AniosService } from '../../../servicios/anios.service';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';


@Component({
  selector: 'app-inicio-cierre-mensual',
  templateUrl: './inicio-cierre-mensual.component.html',
  styleUrls: ['./inicio-cierre-mensual.component.css']
})
export class InicioCierreMensualComponent implements OnInit {

  modalPrincipal;

  anio;anios;
  mes; meses;
  monto;
  f_anio;
  f_anios;
  modalReference;
  load: boolean = false;
  url;
  token = localStorage.getItem('token').replace(/['"]+/g, '');

  //datos_validacion_usuario-
  user: object = [];
  rut: string = '';
  password: string = '';
  //-------------------------

  tabla;

  constructor(config: NgbModalConfig, 
    private modalService: NgbModal,
    private _cbe: BienestarService,
    private _time: AniosService,
    public _http: HttpClient
    
    ) {

    config.backdrop = 'static';
    config.keyboard = false;
    this.url = global.url;
  }

  ngOnInit() {
  
  }

  openMensual(Mensual) {
    this.modalPrincipal = this.modalService.open(Mensual, { size: 'lg' });

    this.llenar_select();
    this.usuario_logeado();

    //console.log(document.getElementById('anio').options.value);
    
  }

  llenar_select(){
      
    this._time.getAnios().subscribe(
      response => {
        this.anios = response;
        this.f_anios = response;

          this._time.getAnioActual().subscribe(
            (response:{id}) => {
              this.anio = response.id;
              this.f_anio = response.id;

              this.listar();
            },
            error => {
              console.log(error);
            }
          )
      },
      error => {
        console.log(error);
      }
    )

    this._time.getMeses().subscribe(
      response => {
        this.meses = response;

        this._time.getMesActual().subscribe(
          (response: { id }) => {
            this.mes = response.id;
          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )
  }

  guardar(){

    this._cbe.guardar_iniciomensual(this.anio, this.mes, this.monto).subscribe(

        (response) => {
          if (response.estado == 'success') {
            alert("Monto inicial guardado");
            this.monto = '';
          }else{
            alert("Error al guardar");
          }
        },
        error => {
          console.log(error);
        }
    );
  }


  usuario_logeado() {

    this._http.get(this.url + "usuario_logeado", {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    ).subscribe((val: object) => {

      this.user = val;

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }

  validar_usuario(validar) {
    this.modalReference = this.modalService.open(validar, { size: 'sm' });
  }

  btn_validar_usuario($rut, $password, validar) {//btn que esta en el modal de validacion de usuario
    this.load = true;
    const formData = new FormData();
    formData.append('rut', $rut.value);
    formData.append('password', $password.value);

    this._http.post(this.url + "confirmar_usuario", formData, {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
    }
    ).subscribe((val) => {

      if (val > 0) {//si tiene acceso;
        this.guardar();
        this.load = false;
        // this.modalReference.close();
        //this.modalService.dismissAll();
        this.modalReference.close();
      } else {
        alert("Acceso denegado");
        this.load = false;
        this.modalReference.close();
      }

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }

  calcular(){
      this._cbe.calcular(this.anio, this.mes).subscribe(

        (response) => {
          this.monto = response;
        },
        error => {
          
        }
      );
  }

  listar(){
    this._cbe.listar_inicio_cierre(this.f_anio).subscribe(
      (response) =>{

        this.tabla = response;

      }

    );
  }

}
