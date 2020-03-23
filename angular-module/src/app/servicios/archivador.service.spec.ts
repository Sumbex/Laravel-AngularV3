import { TestBed } from '@angular/core/testing';

import { ArchivadorService } from './archivador.service';

describe('ArchivadorService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ArchivadorService = TestBed.get(ArchivadorService);
    expect(service).toBeTruthy();
  });
});
