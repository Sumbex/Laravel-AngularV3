import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';
import { Anios } from 'src/app/modelos/anios.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-actas-socio',
  templateUrl: './actas-socio.component.html',
  styleUrls: ['./actas-socio.component.css']
})
export class ActasSocioComponent implements OnInit {

  cabeza: string;
  modal_tabla = null;
  public url: string;

  actas;

  token = localStorage.getItem('token').replace(/['"]+/g, '');

  selectAnio: Anios[] = [];
  idAnioActual;
  suc_res1 = false;
  valorAnio: Anios = {
    descripcion: ''
  };
  anio_load = true;

  constructor(private modalService2: NgbModal, public _http: HttpClient, private _fechasService: AniosService) { 
    this.url = global.url;
  }

  ngOnInit() {

    this.cargar_select();
    
  }

  modal_detalle(modal) {
    this.modal_tabla = this.modalService2.open(modal, { size: 'xl' });
  }

  cargar_select() {
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
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
  

  listar(res1, anio) {
    if (res1 == true) {
      this.anio_load = true;
      this._http.get(this.url + "listar_acta/" + anio, {
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
  
    this.listar(true, this.valorAnio.descripcion);
  }

}
