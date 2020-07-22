import { TestBed } from '@angular/core/testing';

import { ActividadesDirectorioService } from './actividades-directorio.service';

describe('ActividadesDirectorioService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ActividadesDirectorioService = TestBed.get(ActividadesDirectorioService);
    expect(service).toBeTruthy();
  });
});
