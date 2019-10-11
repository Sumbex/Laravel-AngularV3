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

  //Variable para las cargas
  ingresandoDatos = false;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _secretaruaService: SecretariaService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModal(modalHistorial){
    this.modalVariable = this.modalService.open(modalHistorial, {size: 'xl'});
  }

  ingresarReunion(form){
    if(!form){
      alert('Ha ocurrido un error con el formulario, comprobar que no tenga errores');
    }else{
      this.ingresandoDatos = true;
      this._secretaruaService.setNuevaReunion(this.datosReunion).subscribe(response => {
        if(response.estado == 'failed' || response.estado == 'failed_v'){
          alert(response.mensaje);
          this.ingresandoDatos = false;
        }else{
          alert(response.mensaje);
          this.limpiarCampos();
          this.ingresandoDatos = false;
          document.getElementById('botonCerrarModal').click();
        }
      },error=>{
        console.log((error));
        this.ingresandoDatos = false;
      });
    }
  }

  limpiarCampos(){
    this.datosReunion.fecha = '';
    this.datosReunion.descripcion = '';
    this.datosReunion.tipoReunion = '1';
    this.datosReunion.titulo = '';
  }

}
