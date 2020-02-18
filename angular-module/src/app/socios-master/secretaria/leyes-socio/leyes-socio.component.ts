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
  cargarPaginator = false;

  totalPages = 0;
  currentPage = 1;
  range = [];

  constructor(private _leyesService: LeyesService) { }

  ngOnInit() {
    this.traerNoticias(null);
  }

  traerNoticias(pagina) {
    this._leyesService.traerNoticiasSocio(pagina).subscribe(res => {
      if (res.estado == 'success') {
        this.noticias = res.noticias.data;
        this.totalPages = res.noticias.last_page;
        this.currentPage = res.noticias.current_page;

        var pages = [];
        for (let i = 1; i <= this.totalPages; i++) {
          pages.push(i);

        }
        this.range = pages;

        this.ultimaNoticia = res.ultima_noticia;
        this.mostrar = true;
      } else {

        alert(res.mensaje);
      }
    }, error => {

      console.log((error));
    });
  }
}

