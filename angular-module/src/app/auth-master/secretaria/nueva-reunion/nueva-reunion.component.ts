import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SecretariaService } from 'src/app/servicios/secretaria.service';

@Component({
  selector: 'app-nueva-reunion',
  templateUrl: './nueva-reunion.component.html',
  styleUrls: ['./nueva-reunion.component.css']
})
export class NuevaReunionComponent implements OnInit {

  //variable para alojar los datos del mensaje
  datosReunion = {
    fecha: '',
    tipoReunion: '1',
    titulo: '',
    descripcion: ''
  };

  //variable para asociar al modal
  modalVariable;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _secretaruaService: SecretariaService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  ingresarReunion(form){
    if(!form){
      alert('Ha ocurrido un error con el formulario, comprobar que no tenga errores');
    }else{
      this._secretaruaService.setNuevaReunion(this.datosReunion).subscribe(response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
        }else{
          alert(response.mensaje);
        }
      },error=>{
        console.log((error));
        
      });
    }
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

}
