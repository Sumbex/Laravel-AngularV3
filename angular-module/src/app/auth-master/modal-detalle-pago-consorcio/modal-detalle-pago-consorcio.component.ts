import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-modal-detalle-pago-consorcio',
  templateUrl: './modal-detalle-pago-consorcio.component.html',
  styleUrls: ['./modal-detalle-pago-consorcio.component.css']
})
export class ModalDetallePagoConsorcioComponent implements OnInit {

  constructor(config: NgbModalConfig, private modalService: NgbModal,) { }
  modal;
  monto_inicio=105000;
  tabla=[
    {
      fecha:'10/02/2020',
      socio:'Alejandro Godoy',
      descripcion: 'Texto de prueba',
      monto:56000,
      
    },
    {
      fecha: '10/02/2020',
      socio: 'Alejandro Godoy',
      descripcion: 'Texto de prueba',
      monto: 56000
   
    },
  ];
  ngOnInit() {
    console.log('el kkck',this.tabla)
  }

  openModal(modal) {
    this.modal = this.modalService.open(modal, { size: 'xl' });
  
  }

}
