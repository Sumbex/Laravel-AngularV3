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
      descripcion: 'Se le cancela a alejandro por limite de periodo',
      estado:'pagado',
      monto:'130.000',
      
    },
    {
      fecha: '10/02/2020',
      socio: 'Esteban Troncozo',
      descripcion: 'Se le cancela a esteban por limite de periodo',
      estado: 'pagado',
      monto: '230.000'
   
    },

    {
      fecha: '10/02/2020',
      socio: 'Andres Riffo',
      descripcion: 'Se le cancela a andres por limite de periodo',
      estado: 'pagado',
      monto: '10.000'

    },
  ];
  ngOnInit() {
    console.log('el kkck',this.tabla)
  }

  openModal(modal) {
    this.modal = this.modalService.open(modal, { size: 'xl' });
  
  }

}
