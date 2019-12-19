import { Component, OnInit } from '@angular/core';
import { LeyesService } from 'src/app/servicios/leyes.service';

@Component({
  selector: 'app-leyes-socio',
  templateUrl: './leyes-socio.component.html',
  styleUrls: ['./leyes-socio.component.css']
})
export class LeyesSocioComponent implements OnInit {

  noticias;
  ultimaNoticia;
  mostrar = false;

  constructor(private _leyesService: LeyesService) { }

  ngOnInit() {
    this.traerNoticias();
  }

  traerNoticias() {
    this._leyesService.traerNoticiasSocio().subscribe(res => {
      if (res.estado == 'success') {
        this.noticias = res.noticias;
        this.ultimaNoticia = res.ultima_noticia;
        this.mostrar = true;
        console.log(this.ultimaNoticia);
      } else {
        alert(res.mensaje);
      }
    }, error => {
      console.log((error));
    });
  }
}

