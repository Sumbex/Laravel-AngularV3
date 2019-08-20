import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-cambio-contrasenia-socio',
  templateUrl: './cambio-contrasenia-socio.component.html',
  styleUrls: ['./cambio-contrasenia-socio.component.css']
})
export class CambioContraseniaSocioComponent implements OnInit {

  //Variable para asociar el modal
  modalCambioPass;

  constructor(config: NgbModalConfig, private modalService: NgbModal,) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  openModalPassCambio(cambioPass){
    this.modalCambioPass = this.modalService.open(cambioPass, {size: 'lg'});
  }

  cambiarPass(){
    //Aquí llamar al servicio para cambiar pass
    alert('Llamar a cambiar contraseña');
  }

}
