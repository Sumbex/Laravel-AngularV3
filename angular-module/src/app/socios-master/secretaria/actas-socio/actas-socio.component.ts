import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';

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

  constructor(private modalService2: NgbModal, public _http: HttpClient) { 
    this.url = global.url;
  }

  ngOnInit() {
    this.listar();
  }

  modal_detalle(modal) {
    this.modal_tabla = this.modalService2.open(modal, { size: 'xl' });
  }

  listar() {

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

}
