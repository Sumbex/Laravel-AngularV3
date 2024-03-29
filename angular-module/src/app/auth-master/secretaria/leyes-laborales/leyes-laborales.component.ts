import { Component, OnInit } from '@angular/core';
import { LeyesService } from 'src/app/servicios/leyes.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-leyes-laborales',
  templateUrl: './leyes-laborales.component.html',
  styleUrls: ['./leyes-laborales.component.css']
})
export class LeyesLaboralesComponent implements OnInit {

  datosNoticia = {
    titulo: '',
    cuerpo: '',
    foto: null,
    estado: 1
  }
  modalVariable;

  limpiarDatos() {
    this.datosNoticia.titulo = '';
    this.datosNoticia.cuerpo = '';
    this.datosNoticia.foto = null;
    this.datosNoticia.estado = 1;
  }

  ingresandoDatos = false;

  constructor(private _leyesService: LeyesService, config: NgbModalConfig, private modalService: NgbModal, ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  onSelectImage(event) {
    this.datosNoticia.foto = event.srcElement.files[0];
  }

  ingresarLeyes(ley) {
    if (!ley) {
      alert('Error de formulario.');
    } else {
      console.log(this.datosNoticia.foto);
      this.ingresandoDatos = true;
      this._leyesService.ingresarNoticia(this.datosNoticia).subscribe(res => {
        if (res.estado == 'success') {
          alert(res.mensaje);
          this.limpiarDatos();
          this.ingresandoDatos = false;
        } else {
          alert(res.mensaje);
          this.ingresandoDatos = false;
        }
      }, error => {
        console.log((error));
        this.ingresandoDatos = false;
      });
    }
  }

  abrirModalNoticias(modalMenu) {
    this.modalVariable = this.modalService.open(modalMenu, { size: 'xl' });
  }

}
