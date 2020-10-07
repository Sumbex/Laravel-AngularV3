import { Component, OnInit } from '@angular/core';
import { ReunionesService } from 'src/app/servicios/reuniones.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-general-reunion',
  templateUrl: './general-reunion.component.html',
  styleUrls: ['./general-reunion.component.css']
})
export class GeneralReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosReunion;
  datosReunionPasada;

  //Variable para alojar la lista de asistencia
  datosAsistencias;

  //variable para asociar al modal
  modalVariable;

  constructor(private _reunionesService: ReunionesService, config: NgbModalConfig, private modalService: NgbModal) { }

  ngOnInit() {
    this.getDetalleReunion();
    this.getHistorialReuniones();
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

  //OBTENER LA REUNIÓN ACTIVA
  getDetalleReunion(){
    this._reunionesService.getReunionActiva().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        console.log("Reunión inactiva");
      }else{
        this.datosReunion = response.reunion;
        console.log(this.datosReunion);
      }
    }, error => {
      console.log(error);
    });
  }

  getHistorialReuniones(){
    this._reunionesService.getHistorialReunion().subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        // alert(response.mensaje);
        console.log(this.datosReunionPasada);
      }else{
        this.datosReunionPasada = response.reuniones;
        console.log(this.datosReunionPasada);
      }
    }, error => {
      console.log(error);
    });
  }

  getListaReunion(id){
    this._reunionesService.getListaAsistencia(id).subscribe(response =>{
      if(response.estado == 'failed' || response.estado == "failed_v"){
        alert(response.mensaje);
      }else{
       this.datosAsistencias = response.lista;
       console.log(this.datosAsistencias);
      }
    },error=>{
      console.log(error);
    });
  }

}
