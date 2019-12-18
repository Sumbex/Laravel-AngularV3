import { Component, OnInit } from '@angular/core';
import { LeyesService } from 'src/app/servicios/leyes.service';

@Component({
  selector: 'app-leyes-laborales',
  templateUrl: './leyes-laborales.component.html',
  styleUrls: ['./leyes-laborales.component.css']
})
export class LeyesLaboralesComponent implements OnInit {

  datosNoticia = {
    titulo: '',
    cuerpo: '',
    foto: null
  };

  limpiarDatos() {
    this.datosNoticia.titulo = '';
    this.datosNoticia.cuerpo = '';
    this.datosNoticia.foto = null;
  };

  ingresandoDatos = false;

  constructor(private _leyesService: LeyesService) { }

  ngOnInit() {
  }

  onSelectImage(event) {
    this.datosNoticia.foto = event.srcElement.files[0];
  }

  ingresarLeyes(ley) {
    console.log(this.datosNoticia);
    if (!ley) {
      alert('error');
    } else {
      this.ingresandoDatos = true;
      this._leyesService.ingresarNoticia(this.datosNoticia).subscribe(res => {
        if (res.estado = 'failed') {
          alert(res.mensaje);
          this.ingresandoDatos = false;
        } else {
          alert(res.mensaje);
          this.limpiarDatos();
          this.ingresandoDatos = false;
        }
      }, error => {
        console.log((error));
        this.ingresandoDatos = false;
      });
    }
  }

}
